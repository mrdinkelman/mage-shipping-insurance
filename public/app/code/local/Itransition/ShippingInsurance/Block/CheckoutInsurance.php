<?php

class Itransition_ShippingInsurance_Block_CheckoutInsurance extends Mage_Checkout_Block_Onepage_Abstract
{
    public function getInsuranceCost()
    {
        $quote = $this->getQuote();
        $cost  = Mage::helper('core')->currency($quote->getShippingInsurance(), true, false);

        return $cost;
    }

    public function isModuleEnabled()
    {
        return Mage::getStoreConfig('shippinginsurance/settings/enabled');
    }

    public function isShippingMethodAllowed()
    {
        $quote           = $this->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $shippingMethod  = $shippingAddress->getShippingMethod();

        return Mage::getStoreConfig(sprintf(
            'shippinginsurance/rates/field_%s_enabled',
            $shippingMethod
        ));
    }
}
