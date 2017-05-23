<?php

class Itransition_ShippingInsurance_Model_Observer
{
    public function checkoutControllerOnepageSaveShippingMethod(Varien_Event_Observer $observer)
    {
        /** @var $helper Itransition_ShippingInsurance_Helper_Data $helper */
        $helper   = Mage::helper('itransition_shippinginsurance');
        $accepted = $observer->getRequest()->getParam('shippinginsurance_enabled', false);

        $quote           = $observer->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $shippingMethod  = $shippingAddress->getShippingMethod();
        $shippingRates   = $shippingAddress->collectShippingRates()->getGroupedAllShippingRates();

        $shippingAddress->setInsuranceShippingMethod(null);
        $shippingAddress->setShippingInsurance(0);

        $quote->setInsuranceShippingMethod(null);
        $quote->setShippingInsurance(0);

        if (!$helper->isFeatureEnabled() || !$accepted) {
            return $this;
        }

        $carrierCode = null;

        foreach ($shippingRates as $rate) {
            foreach ($rate as $carrier) {
                if ($shippingMethod === $carrier->getCode()) {
                    $carrierCode = $carrier->getCarrier();
                }
            }
        }

        // carrier code not found or not allowed by settings, @todo improve in future
        if (!$carrierCode) {
            return $this;
        }

        $allowed = $helper->isCarrierCodeAllowed($carrierCode);

        if (!$allowed) {
            return $this;
        }

        $cost = $helper->calculateInsuranceCost($carrierCode, $quote->getSubtotal());

        $shippingAddress->setInsuranceShippingMethod($carrierCode);
        $shippingAddress->setShippingInsurance($cost);

        $quote->setInsuranceShippingMethod($carrierCode);
        $quote->setShippingInsurance($cost);

        return $this;
    }
}
