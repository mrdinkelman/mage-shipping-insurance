<?php

class Itransition_ShippingInsurance_Model_Total_Invoice extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order           = $invoice->getOrder();
        $costInsurance   = $order->getShippingInsurance();
        $isModuleEnabled = Mage::getStoreConfig('shippinginsurance/settings/enabled');

        if ($isModuleEnabled && $order->getInsuranceShippingMethod()) {
            $invoice->setGrandTotal($invoice->getGrandTotal() + $costInsurance);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $costInsurance);
        }

        return $this;
    }
}