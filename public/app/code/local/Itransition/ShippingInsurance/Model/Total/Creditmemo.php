<?php

class Itransition_ShippingInsurance_Model_Total_Creditmemo extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        /** @var $helper Itransition_ShippingInsurance_Helper_Data $helper */
        $helper           = Mage::helper('itransition_shippinginsurance');
        $isFeatureEnabled = $helper->isFeatureEnabled();
        $order            = $creditmemo->getOrder();
        $costInsurance    = $order->getShippingInsurance();

        if ($isFeatureEnabled && $order->getInsuranceShippingMethod()) {
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $costInsurance);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $costInsurance);
        }

        return $this;
    }
}
