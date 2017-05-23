<?php

class Itransition_ShippingInsurance_Model_Total_Invoice extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        /** @var $helper Itransition_ShippingInsurance_Helper_Data $helper */
        $helper           = Mage::helper('itransition_shippinginsurance');
        $isFeatureEnabled = $helper->isFeatureEnabled();
        $order            = $invoice->getOrder();
        $costInsurance    = $order->getShippingInsurance();

        if ($isFeatureEnabled && $order->getInsuranceShippingMethod()) {
            $invoice->setGrandTotal($invoice->getGrandTotal() + $costInsurance);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $costInsurance);
        }

        return $this;
    }
}