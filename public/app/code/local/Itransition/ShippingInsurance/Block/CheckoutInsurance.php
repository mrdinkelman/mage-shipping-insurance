<?php

class Itransition_ShippingInsurance_Block_CheckoutInsurance extends Mage_Checkout_Block_Onepage_Abstract
{
    public function isModuleEnabled()
    {
        return Mage::getStoreConfig('shippinginsurance/settings/enabled');
    }

    public function hasInsurances()
    {
        return 0 < count($this->listInsuranceCosts());
    }

    public function listInsuranceCosts()
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $this->getQuote();

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

            if (!$this->_isInsuranceEnabled($carrierCode)) {
                continue;
            }

            $costInsurance        = $this->_calculateInsuranceCost($carrierCode);
            $costs[$carrierTitle] = Mage::helper('core')->currency($costInsurance, true, false);
        }

        return $costs;
    }

    protected function _isInsuranceEnabled($code)
    {
        return Mage::getStoreConfig('shippinginsurance/rates/field_' . $code. '_enabled');
    }

    protected function _calculateInsuranceCost($code)
    {
        $costInsurance = 0;
        $subTotal      = $this->getQuote()->getSubtotal();

        $type = Mage::getStoreConfig('shippinginsurance/rates/field_' . $code . '_type');
        $fee  = Mage::getStoreConfig('shippinginsurance/rates/field_' . $code . '_fee');

        switch ($type) {
            case Itransition_ShippingInsurance_Model_Setting_Source_Type::TYPE_FIXED_ID: {
                $costInsurance = round($fee, 2, PHP_ROUND_HALF_UP);
                break;
            }
            case Itransition_ShippingInsurance_Model_Setting_Source_Type::TYPE_ORDER_PERCENTAGE_ID: {
                $costInsurance = round($subTotal * ($fee / 100), 2, PHP_ROUND_HALF_UP);
            }
        }

        return $costInsurance;
    }
}
