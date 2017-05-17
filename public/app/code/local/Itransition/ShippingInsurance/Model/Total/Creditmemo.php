<?php

class Itransition_ShippingInsurance_Model_Total_Creditmemo extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order           = $creditmemo->getOrder();
        $costInsurance   = $order->getShippingInsurance();
        $isModuleEnabled = Mage::getStoreConfig('shippinginsurance/settings/enabled');

        if ($isModuleEnabled && $order->getInsuranceShippingMethod()) {
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $costInsurance);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $costInsurance);
        }

        return $this;
    }
}
