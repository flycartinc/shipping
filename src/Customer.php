<?php
/**
 * Created by PhpStorm.
 * User: flycart6
 * Date: 1/7/16
 * Time: 2:06 PM
 */
namespace StorePress\flycartinc\Shipping;

use StorePress\flycartinc\Shipping\sampleData;

class Customer
{

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        //sample data must be changed to session data
        $this->shipping_method_data = (new sampleData())->shipping_method_data();
        $this->zone_data = (new sampleData())->zone_data();
    }

    public function setCustomerData()
    {
        $this->customerData = $this->shipping_method_data;

        if ($this->customerData) {

            if ($this->customerData['address']) {
                $this->setCustomerAddress($this->customerData['address']);
            }

            if ($this->customerData['billingAddress']) {
                $this->setCustomerBillingAddress($this->customerData['billingAddress']);
            }

            if ($this->customerData['state']) {
                $this->setCustomerState($this->customerData['state']);
            }

            if ($this->customerData['country']){
                $this->setCustomerCountry($this->customerData['country']);
            }

            if ($this->customerData['zip']){
                $this->setCustomerZip($this->customerData['zip']);
            }

        }
    }

    private function setCustomerAddress($address)
    {
        $this->customerAddress = $address;
    }

    private function setCustomerBillingAddress($billingAddress){
        $this->customerbillingAddress = $billingAddress;
    }

    private function setCustomerState($state)
    {
        $this->customerState = $state;
    }

    private function setCustomerCountry()
    {
        $this->customerCountry = $this->customerData['country'];
    }

    private function setCustomerZip()
    {
        $this->customerZip = $this->customerData['zip'];
    }

    public function getCustomerData()
    {
        return $this->customer_data;
    }

    public function getCustomerAddress()
    {
        return $this->customerAddress;
    }

    public function getCustomerBillingAddress()
    {
        return $this->customerbillingAddress;
    }

    public function getCustomerState()
    {
        return $this->customerState;
    }

    public function getCustomerCountry()
    {
        return $this->customerCountry;
    }

    public function getCustomerZip()
    {
        return $this->customerZip;
    }

    public function getCustomerZone(){
        
    }

    public function getCustomerSessionData(){
        return $this->session('customerCartData');
    }

    public function issetCustomerSession()
    {
        //to check whether the data is occuring in session
        if(! empty($this->session('customerCartData')))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}