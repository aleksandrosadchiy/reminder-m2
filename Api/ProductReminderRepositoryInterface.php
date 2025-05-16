<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\ProductReminder\Api;

interface ProductReminderRepositoryInterface
{
    /**
     * @param \Vendor\ProductReminder\Api\Data\ProductReminderInterface $productReminder
     * @return \Vendor\ProductReminder\Api\Data\ProductReminderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Vendor\ProductReminder\Api\Data\ProductReminderInterface $productReminder
    ): \Vendor\ProductReminder\Api\Data\ProductReminderInterface;

    /**
     * Retrieve product reminder by id.
     *
     * @param int $reminderId
     * @return \Vendor\ProductReminder\Api\Data\ProductReminderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get(int $reminderId): \Vendor\ProductReminder\Api\Data\ProductReminderInterface;

    /**
     * Retrieve product reminders matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Vendor\ProductReminder\Api\Data\ProductReminderSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    ): \Magento\Framework\Api\SearchResultsInterface;

    /**
     * Delete product reminder.
     *
     * @param \Vendor\ProductReminder\Api\Data\ProductReminderInterface $productReminder
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Vendor\ProductReminder\Api\Data\ProductReminderInterface $productReminder
    ): void;

    /**
     * Delete product reminder by id.
     *
     * @param int $reminderId
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $reminderId): void;
}

