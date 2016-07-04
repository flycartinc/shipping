<?php
/**
 * Created by PhpStorm.
 * User: flycart6
 * Date: 1/7/16
 * Time: 4:39 PM
 */
namespace StorePress\flycartinc\Shipping;

use StorePress\flycartinc\Shipping\Zone;

class ShippingMethods {
    
    public function init()
    { 
        $this->shippingMethods = (new Zone())->getZoneEligibility(); 
    }
    
    public function isEligible()
    {
    }

    public function isFlatRate()
    {
    }

    public function isLocalPickup()
    {
    }

    public function isFreeShipping()
    {
    }
}