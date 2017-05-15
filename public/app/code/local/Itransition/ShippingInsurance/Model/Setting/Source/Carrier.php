<?php

class Itransition_ShippingInsurance_Model_Setting_Source_Carrier
{
    public function toOptionArray()
    {
        $options  = array();
        $carriers = Mage::getSingleton('shipping/config')->getAllCarriers();

        /** @var  $carrier Mage_Shipping_Model_Carrier_Abstract */
        foreach ($carriers as $carrier)
        {
            $options[] = array(
                'value' => $carrier->getId(),
                'label' => $this->_getLabel($carrier)
            );
        }

        return $options;
    }

    protected function _getLabel(Mage_Shipping_Model_Carrier_Abstract $carrier)
    {
        $label = Mage::getStoreConfig(sprintf(
            'carriers/%s/title',
            $carrier->getCarrierCode()
        ));

        if (!$this->_isActive($carrier)) {
            $label = sprintf('%s [Is not Active]', $label);
        }

        return $label;
    }

    protected function _isActive(Mage_Shipping_Model_Carrier_Abstract $carrier)
    {
        return true === (bool) Mage::getStoreConfig(sprintf('carriers/%s/active', $carrier->getCarrierCode()));
    }
}
