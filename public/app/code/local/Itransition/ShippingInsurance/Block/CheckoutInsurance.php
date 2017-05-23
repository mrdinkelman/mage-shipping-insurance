<?php

class Itransition_ShippingInsurance_Block_CheckoutInsurance extends Mage_Checkout_Block_Onepage_Abstract
{
    public function isFeatureEnabled()
    {
        /** @var $helper Itransition_ShippingInsurance_Helper_Data $helper */
        $helper = Mage::helper('itransition_shippinginsurance');

        return $helper->isFeatureEnabled();
    }

    public function listInsuranceCosts()
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote  = $this->getQuote();

        /** @var $helper Itransition_ShippingInsurance_Helper_Data $helper */
        $helper = Mage::helper('itransition_shippinginsurance');

        /** @var Mage_Sales_Model_Quote_Address $shippingAddress */
        $shippingAddress = $quote->getShippingAddress();
        $rates           = $shippingAddress->collectShippingRates()->getGroupedAllShippingRates();
        $costs           = array();

        foreach ($rates as $code => $rate) {
            if (!$helper->isCarrierCodeAllowed($code)) {
                continue;
            }

            $carrier = array_shift($rate);
            $carrierCode  = $carrier->getCarrier();
            $carrierTitle = $carrier->getCarrierTitle();

            $costInsurance        = $helper->calculateInsuranceCost($carrierCode, $this->getQuote()->getSubtotal());
            $costs[$carrierTitle] = Mage::helper('core')->currency($costInsurance, true, false);
        }

        return $costs;
    }
}
