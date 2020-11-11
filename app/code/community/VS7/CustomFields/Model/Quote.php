<?php

class VS7_CustomFields_Model_Quote extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('vs7_customfields/quote');
    }

    public function deleteByQuote($quote_id, $var)
    {
        $this->_getResource()->deteleByQuote($quote_id, $var);
    }

    public function getByQuote($quote_id, $var = '')
    {
        return $this->_getResource()->getByQuote($quote_id, $var);
    }
}