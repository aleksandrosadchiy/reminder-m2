<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\ProductReminder\Observer\Catalog;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;

class ProductDeleteAfterDone implements ObserverInterface
{
    /**
     * @param \Vendor\ProductReminder\Api\ProductReminderRepositoryInterface $reminderRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Vendor\ProductReminder\Api\Config\ProductReminderConfigInterface $config
     */
    public function __construct(
        private readonly \Vendor\ProductReminder\Api\ProductReminderRepositoryInterface $reminderRepository,
        private readonly \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly \Psr\Log\LoggerInterface $logger,
        private readonly \Vendor\ProductReminder\Api\ProductReminderConfigInterface $config,
    ) {}

    /**
     * Remove associated reminders when a product is deleted.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if (!$this->config->isEnabled()) {
            return;
        }

        $product = $observer->getEvent()->getProduct();
        $productId = $product->getId();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(\Vendor\ProductReminder\Api\Data\ProductReminderInterface::PRODUCT_ID, $productId)
            ->create();

        $reminders = $this->reminderRepository->getList($searchCriteria)->getItems();

        try {
            foreach ($reminders as $reminder) {
                $this->reminderRepository->delete($reminder);
            }
        } catch (\Throwable $e) {
            $this->logger->error('Failed to delete product reminders for product ID ' . $productId . ': ' . $e->getMessage());
        }
    }
}

