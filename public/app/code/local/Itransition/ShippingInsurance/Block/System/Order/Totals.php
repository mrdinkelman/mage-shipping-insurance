<?php

class Itransition_ShippingInsurance_Block_System_Order_Totals extends Mage_Adminhtml_Block_Sales_Order_Totals
{
    protected $_code = 'shipping_insurance';

    protected function _initTotals()
    {
        parent::_initTotals();

        Mage::helper('itransition_shippinginsurance')->rewriteTotals($this);

        return $this;
    }
}
