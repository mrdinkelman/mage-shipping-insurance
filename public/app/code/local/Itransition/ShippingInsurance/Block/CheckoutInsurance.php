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
        $rates           = $shippingAddress->getShippingRatesCollection();
        $costs           = array();

        /** @var Mage_Sales_Model_Quote_Address_Rate $rate */
        foreach ($rates as $rate) {
            $carrierCode  = $rate->getCarrier();
            $carrierTitle = $rate->getCarrierTitle();

            if (isset($costs[$carrierTitle])) {
                continue;
            }

            if (!$helper->isCarrierCodeAllowed($carrierCode)) {
                continue;
            }

            $costInsurance        = $helper->calculateInsuranceCost($carrierCode, $this->getQuote()->getSubtotal());
            $costs[$carrierTitle] = Mage::helper('core')->currency($costInsurance, true, false);
        }

        return $costs;
    }
}
