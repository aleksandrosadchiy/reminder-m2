<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\ProductReminder\Model;

use Vendor\ProductReminder\Api\Data\ProductReminderInterface;

class ProductReminderManagement implements \Vendor\ProductReminder\Api\ProductReminderManagementInterface
{
    /**
     * @param \Vendor\ProductReminder\Api\ProductReminderRepositoryInterface $reminderRepository
     * @param \Vendor\ProductReminder\Model\ProductReminderFactory $reminderFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     */
    public function __construct(
        private readonly \Vendor\ProductReminder\Api\ProductReminderRepositoryInterface $reminderRepository,
        private readonly \Vendor\ProductReminder\Model\ProductReminderFactory $reminderFactory,
        private readonly \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly \Magento\Framework\Api\FilterBuilder $filterBuilder
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function setReminder($reminder)
    {
        $reminderDate = new \DateTime($reminder->getReminderDate());
        $currentDate = new \DateTime();

        if ($reminderDate <= $currentDate) {
            throw new \Magento\Framework\Exception\InputException(
                __('Reminder date must be in the future.')
            );
        }

        $customerId = (int) $reminder->getCustomerId();
        $reminderEntity = $this->reminderFactory->create();
        $reminderEntity->setCustomerId($customerId)
            ->setProductId((int) $reminder->getProductId())
            ->setReminderDate($reminderDate->format('Y-m-d'))
            ->setStatus(ProductReminderInterface::STATUS_PENDING);

        try {
            $this->reminderRepository->save($reminderEntity);
        } catch (\Throwable $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not save the reminder: %1', $e->getMessage())
            );
        }

        return $reminderEntity;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerReminders($customerId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(ProductReminderInterface::CUSTOMER_ID, $customerId)
            ->create();

        $reminders = $this->reminderRepository->getList($searchCriteria);

        return $reminders->getItems();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteReminder($id)
    {
        if (!$this->reminderRepository->get($id)) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('Reminder with id "%1" does not exist.', $id)
            );
        }

        $this->reminderRepository->deleteById((int) $id);
    }

    /**
     * @inheritDoc
     */
    public function getReminderByDate(?string $date = null): array
    {
        $reminderDate = (new \DateTime())->format('Y-m-d');
        $dateFilter = $this->filterBuilder->setField(ProductReminderInterface::REMINDER_DATE)
            ->setConditionType('eq')
            ->setValue($reminderDate)
            ->create();
        $statusFilter = $this->filterBuilder->setField(ProductReminderInterface::STATUS)
            ->setConditionType('eq')
            ->setValue(ProductReminderInterface::STATUS_PENDING)
            ->create();

        $this->searchCriteriaBuilder->addFilters([$dateFilter, $statusFilter]);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $orderList = $this->reminderRepository->getList($searchCriteria);

        return $orderList->getItems();
    }
}

