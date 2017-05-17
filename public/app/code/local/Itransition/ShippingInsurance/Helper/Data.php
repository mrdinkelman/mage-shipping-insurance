<?php

class Itransition_ShippingInsurance_Helper_Data extends Mage_Core_Helper_Data
{
    const LABEL = 'Shipping Insurance label';

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
                        'label'      => self::LABEL
                    ),
                    'grand_total'
                )
            );
        }
    }
}
