<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\ProductReminder\Model\ResourceModel\ProductReminder;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Vendor\ProductReminder\Model\ProductReminder::class,
            \Vendor\ProductReminder\Model\ResourceModel\ProductReminder::class
        );
    }
}

