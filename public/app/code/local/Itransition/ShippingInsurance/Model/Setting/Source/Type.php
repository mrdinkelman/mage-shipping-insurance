<?php

class Itransition_ShippingInsurance_Model_Setting_Source_Type
{
    const TYPE_FIXED_ID  = 1;
    const TYPE_FIXED_HDL = 'Fixed amount';

    const TYPE_ORDER_PERCENTAGE_ID  = 2;
    const TYPE_ORDER_PERCENTAGE_HDL = 'Percentage of Order amount';

    public function toOptionArray()
    {
        $options = array();

        foreach ($this->getTypeIdHdlMap() as $value => $label) {
            $options[] = array('value' => $value, 'label' => $label);
        }

        return $options;
    }

    public function getTypeIdHdlMap()
    {
        return array(
            self::TYPE_FIXED_ID            => self::TYPE_FIXED_HDL,
            self::TYPE_ORDER_PERCENTAGE_ID => self::TYPE_ORDER_PERCENTAGE_HDL,
        );
    }
}
