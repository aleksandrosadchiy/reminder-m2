<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\ProductReminder\Api;

interface ProductReminderManagementInterface
{
    /**
     * Create product reminder for customer.
     *
     * @param \Vendor\ProductReminder\Api\Data\ProductReminderInterface $reminder
     * @return \Vendor\ProductReminder\Api\Data\ProductReminderInterface
     */
    public function setReminder($reminder);

    /**
     * Get customer's reminders.
     *
     * @param int|string $customerId
     * @return \Vendor\ProductReminder\Api\Data\ProductReminderInterface[]
     */
    public function getCustomerReminders($customerId);

    /**
     * Delete customer's reminder by id.
     *
     * @param int $id
     * @return string
     */
    public function deleteReminder($id);

    /**
     * Get reminders by date.
        *
     * @param string $date
     * @return \Vendor\ProductReminder\Api\Data\ProductReminderInterface[]
     */
    public function getReminderByDate(string $date): array;
}

