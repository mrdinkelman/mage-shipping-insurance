<?php

class Itransition_ShippingInsurance_Block_System_Config_Form_Field_Custom
    extends Mage_Adminhtml_Block_System_Config_Form_Field
    implements Varien_Data_Form_Element_Renderer_Interface
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $prototype = $element->getData('field_config');

        if ($prototype && $prototype instanceof Mage_Core_Model_Config_Element && isset($prototype->label)) {
            $element->setLabel($prototype->label);
        }

        return parent::render($element);
    }
}
