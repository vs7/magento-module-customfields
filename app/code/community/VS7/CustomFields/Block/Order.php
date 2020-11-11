<?php

class VS7_CustomFields_Block_Order extends Mage_Core_Block_Template
{
    public function getCustomVars()
    {
        $model = Mage::getModel('vs7_customfields/order');
        return $model->getByOrder($this->getOrder()->getId());
    }

    public function getOrder()
    {
        return Mage::registry('current_order');
    }
}