<?php

class Itransition_ShippingInsurance_Model_Total_Quote extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    const LABEL = 'Shipping Insurance';

    protected $_code = 'shipping_insurance';

    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);

        $isModuleEnabled = Mage::getStoreConfig('shippinginsurance/settings/enabled');

        if ($isModuleEnabled) {
            $items = $this->_getAddressItems($address);

            if (!count($items)) {
                return $this;
            }

            $costInsurance = $this->getInsuranceCost($address);
            $this->setGrandTotalWithInsuranceCost($address, $costInsurance);
        }
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if ($address->getInsuranceShippingMethod()) {
            $costInsurance = $address->getShippingInsurance();
            $address->addTotal(
                [
                    'code'  => $this->getCode(),
                    'title' => self::LABEL,
                    'value' => $costInsurance
                ]
            );
        }
        return $this;
    }

    private function getInsuranceCost(Mage_Sales_Model_Quote_Address $address)
    {
        $costInsurance  = 0;
        $subTotal       = (float) $address->getSubtotal();
        $shippingMethod = $address->getShippingMethod();

        $type = Mage::getStoreConfig('shippinginsurance/rates/field_' . $shippingMethod . '_type');
        $fee  = Mage::getStoreConfig('shippinginsurance/rates/field_' . $shippingMethod . '_fee');

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

    private function setGrandTotalWithInsuranceCost(Mage_Sales_Model_Quote_Address $address, $costInsurance)
    {
        $quote = $address->getQuote();
        $quote->setShippingInsurance($costInsurance);
        $address->setShippingInsurance($costInsurance);

        if ($address->getInsuranceShippingMethod()) {
            $address->setGrandTotal($address->getGrandTotal() + $address->getShippingInsurance());
            $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getShippingInsurance());
        }
    }
}
