<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\ProductReminder\Model;

use Magento\Framework\Model\AbstractModel;
use Vendor\ProductReminder\Api\Data\ProductReminderInterface;

class ProductReminder extends AbstractModel implements ProductReminderInterface
{
    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Vendor\ProductReminder\Model\ResourceModel\ProductReminder::class);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId(): int
    {
        return (int) $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId($customerId): self
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritDoc
     */
    public function getProductId(): int
    {
        return (int) $this->getData(self::PRODUCT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductId($productId): self
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @inheritDoc
     */
    public function getReminderDate(): string
    {
        return (string) $this->getData(self::REMINDER_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setReminderDate($reminderDate): self
    {
        return $this->setData(self::REMINDER_DATE, $reminderDate);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return (int) $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($status): self
    {
        return $this->setData(self::STATUS, $status);
    }
}

