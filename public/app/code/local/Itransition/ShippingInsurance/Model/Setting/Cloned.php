<?php

class Itransition_ShippingInsurance_Model_Setting_Cloned extends Mage_Core_Model_Config_Data
{
    public function getPrefixes()
    {
        $prefixes = array();
        $carriers = Mage::getSingleton('shipping/config')->getAllCarriers();

        /** @var  $carrier Mage_Shipping_Model_Carrier_Abstract */
        foreach ($carriers as $carrier) {
            $prefixes[] = array(
                'field' => 'field_' . $carrier->getCarrierCode() . '_' . $carrier->getId() . '_',
                'label' => $carrier->getConfigData('title'),
            );
        }
        
        return $prefixes;
    }
}
