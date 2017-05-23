<?php

class Itransition_ShippingInsurance_Helper_Data extends Mage_Core_Helper_Data
{
    public function getTranslatedLabel()
    {
        return $this->__('Shipping Insurance label');
    }

    public function isFeatureEnabled()
    {
        return (bool) Mage::getStoreConfig('shippinginsurance/settings/enabled');
    }

    public function isCarrierCodeAllowed($carrierCode)
    {
        if (!is_string($carrierCode) || empty($carrierCode)) {
            Mage::log('Shipping Method is invalid');

            return false;
        }

        return (bool) Mage::getStoreConfig(sprintf(
            'shippinginsurance/rates/field_%s_enabled',
            $carrierCode
        ));
    }

    public function calculateInsuranceCost($carrierCode, $subTotal)
    {
        if (!is_string($carrierCode) || empty($carrierCode)) {
            Mage::log('Invalid Carrier Code');

            return 0;
        }

        $type = Mage::getStoreConfig('shippinginsurance/rates/field_' . $carrierCode . '_type');
        $fee  = Mage::getStoreConfig('shippinginsurance/rates/field_' . $carrierCode . '_fee');

        switch ($type) {
            case Itransition_ShippingInsurance_Model_Setting_Source_Type::TYPE_FIXED_ID: {
                return round($fee, 2, PHP_ROUND_HALF_UP);
            }
            case Itransition_ShippingInsurance_Model_Setting_Source_Type::TYPE_ORDER_PERCENTAGE_ID: {
                return round($subTotal * ($fee / 100), 2, PHP_ROUND_HALF_UP);
            }
            default:
                Mage::log('Carrier code is not supported or calculation not implemented.');
                return 0;
        }
    }

    public function rewriteTotals($class)
    {
        $order = $class->getOrder();

        if ($order->getInsuranceShippingMethod()) {
            $costInsurance = $order->getShippingInsurance();
            $class->addTotalBefore(
                new Varien_Object(
                    array(
                        'code'       => $class->getCode(),
                        'value'      => $costInsurance,
                        'base_value' => $costInsurance,
                        'label'      => $this->getTranslatedLabel()
                    ),
                    'grand_total'
                )
            );
        }
    }
}
