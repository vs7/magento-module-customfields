<?php

class VS7_CustomFields_Model_Observer
{
    public function saveQuoteBefore($event)
    {
        $quote = $event->getQuote();
        $post = Mage::app()->getFrontController()->getRequest()->getPost();

        if (isset($post['typeMethod'])) {
            if ($post['typeMethod'] == 'legal') {
                $legal = $post['legal'];
                $quote->setLegal($legal);
            }

            if ($post['typeMethod'] == 'physical') {
                $physical = $post['physical'];
                $quote->setPhysical($physical);
            }
            $quote->setTypeMethod($post['typeMethod']);
        }
    }

    public function saveQuoteAfter($event)
    {
        $quote = $event->getQuote();

        //get custom vars
        if ($quote->getTypeMethod() == 'legal') {
            $customFields = $quote->getLegal();
        } else if ($quote->getTypeMethod() == 'physical') {
            $customFields = $quote->getPhysical();
        }

        //save custom fields in the table
        if (!empty($customFields)) {
            foreach ($customFields as $customKey => $customValue) {
                $model = Mage::getModel('vs7_customfields/quote');
                $model->deleteByQuote($quote->getId(), $customKey);
                $model->setQuoteId($quote->getId());
                $model->setKey($customKey);
                $model->setValue($customValue);
                $model->save();
            }
        }
    }

    public function loadQuoteAfter($event)
    {
        $quote = $event->getQuote();
        $model = Mage::getModel('vs7_customfields/quote');
        $data = $model->getByQuote($quote->getId());
        foreach ($data as $key => $value) {
            $quote->setData($key, $value);
        }
    }

    public function saveOrderAfter($event)
    {
        $order = $event->getOrder();
        $quote = $event->getQuote();

        //get custom vars
        if ($quote->getTypeMethod() == 'legal') {
            $customFields = $quote->getLegal();
        } else if ($quote->getTypeMethod() == 'physical') {
            $customFields = $quote->getPhysical();
        }

        //save custom fields in the table
        if (!empty($customFields)) {
            foreach ($customFields as $customKey => $customValue) {
                $model = Mage::getModel('vs7_customfields/order');
                $model->deleteByOrder($order->getId(), $customKey);
                $model->setOrderId($order->getId());
                $model->setKey($customKey);
                $model->setValue($customValue);
                $model->save();
            }
        }
    }

    public function loadOrderAfter($event)
    {
        $order = $event->getOrder();
        $model = Mage::getModel('vs7_customfields/order');
        $data = $model->getByOrder($order->getId());
        foreach ($data as $key => $value) {
            $order->setData($key, $value);
        }
    }

    public function insertCustomFieldsAdmin($event)
    {
        if ($event->getBlock()->getType() == 'adminhtml/sales_order_view_info') {
            $block = Mage::app()->getLayout()->getBlock('order_info.vs7_customfields');
            if ($block) {
                $append = $block->toHtml();
                $html = $event->getTransport()->getHtml();
                $event->getTransport()->setHtml($html . $append);
            }
        }
    }
}