<?php
namespace StorePress\flycartinc\Shipping;
use Events\Event;
use Flycartinc\Cache\Cache;
class Shipping
{
    private function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->zones = new Zone();
        $this->rates = new RateCalculator();
    }
    
    public function calculateShipping($packages = array())
    {
        $this->shipping_total = null;
        $this->shipping_taxes = array();
        $this->packages       = array();

        if ( ! $this->isEnabled() || empty( $packages ) ) {
            return;
        }

        // Calculate costs for passed packages
        foreach ( $packages as $package_key => $package ) {
            $this->packages[ $package_key ] = $this->calclateShippingForPackage( $package, $package_key );
        }
        
        $this->package = array();
        foreach ($packages as $package) {
            foreach ($this->loadShippingMethods($package) as $shipping_method) {
                $this->package[$shipping_method] = $this->rates->getRate($shipping_method);
            }
        }
        return $this->package;
    }

    private function calclateShippingForPackage($package, $package_key)
    {
        //sanity check. Just to be sure
        if (!$this->isEnabled() || empty($package)) {
            return false;
        }

        // Check if we need to recalculate shipping for this package

        $package_hash = 'wc_ship_' . md5(json_encode($package) . Cache::getItem('shipping'));
        $status_options = get_option('woocommerce_status_options', array());
        $session_key = 'shipping_for_package_' . $package_key;
        $stored_rates = WC()->session->get($session_key);

        if (!is_array($stored_rates) || $package_hash !== $stored_rates['package_hash'] || !empty($status_options['shipping_debug_mode'])) {
            // Calculate shipping method rates
            $package['rates'] = array();

            foreach ($this->loadShippingMethods($package) as $shipping_method) {
                // Shipping instances need an ID
                $package['rates'] = $package['rates'] + Event::trigger('getRatesForPackage', array('shipping_method' => $shipping_method), 'filter');
            }

        $package['rates'] = apply_filters( 'woocommerce_package_rates', $package['rates'], $package );
        // Store in session to avoid recalculation
        WC()->session->set( $session_key, array(
            'package_hash' => $package_hash,
            'rates'        => $package['rates']
        ) );
        }else {
            $package['rates'] = $stored_rates['rates'];
        }

        return $package;
    }
    public function loadShippingMethods($package = array())
    {

        if (!empty($package)) {
            $shipping_methods = Event::trigger('loadShippingMethods', array('package'=>$package), 'filter');
            $this->shipping_methods = $this->zones->getZoneEligibility();

            if (!empty($this->shipping_methods)) {
                return $this->shipping_methods;
            } else {
                $this->shipping_methods = array();
                return $this->shipping_methods;
            }
        }
        return array();
    }
}