<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\ProductReminder\Api\Data;

interface ProductReminderInterface
{
    public const ID = 'id';
    public const PRODUCT_ID = 'product_id';
    public const CUSTOMER_ID = 'customer_id';
    public const REMINDER_DATE = 'reminder_date';
    public const STATUS = 'status';

    public const STATUS_PENDING = 0;
    public const STATUS_SENT = 1;

    /**
     * Get product reminder id.
     *
     * @return int
     */
    public function getId();

    /**
     * Get customer id.
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param int|string $customerId
     * @return \Vendor\ProductReminder\Api\Data\ProductReminderInterface
     */
    public function setCustomerId($customerId): self;

    /**
     * Get product id.
     *
     * @return int|null
     */
    public function getProductId(): ?int;

    /**
     * Set product id.
     *
     * @param string|int $productId
     * @return \Vendor\ProductReminder\Api\Data\ProductReminderInterface
     */
    public function setProductId($productId): self;

    /**
     * Get reminder date.
     *
     * @return string|null
     */
    public function getReminderDate();

    /**
     * Set reminder date.
     *
     * @param string $reminderDate
     * @return \Vendor\ProductReminder\Api\Data\ProductReminderInterface
     */
    public function setReminderDate($reminderDate): self;

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Set status
     * @param string|int $status
     * @return \Vendor\ProductReminder\Api\Data\ProductReminderInterface
     */
    public function setStatus($status): self;
}

