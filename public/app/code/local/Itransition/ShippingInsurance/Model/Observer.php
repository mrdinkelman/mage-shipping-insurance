<?php

class Itransition_ShippingInsurance_Model_Observer
{
    public function checkoutObserver(Varien_Event_Observer $observer)
    {
        $isModuleEnabled = Mage::getStoreConfig('shippinginsurance/settings/enabled');

        if ($isModuleEnabled) {
            $quote    = $observer->getQuote();
            $address  = $quote->getShippingAddress();
            $accepted = Mage::app()->getRequest()->getParam('shippinginsurance_enabled', false);

            if ($accepted) {
                $shippingMethod = $address->getShippingMethod();
                $enabled        = Mage::getStoreConfig(sprintf(
                    'shippinginsurance/rates/field_%s_enabled',
                    $shippingMethod
                ));

                if ($enabled) {
                    $address->setInsuranceShippingMethod($shippingMethod);
                    $quote->setInsuranceShippingMethod($shippingMethod);
                }
            }
        }
    }
}
