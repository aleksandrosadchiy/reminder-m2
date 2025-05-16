<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\ProductReminder\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vendor\ProductReminder\Api\Data\ProductReminderInterface;
use Vendor\ProductReminder\Api\Data\ProductReminderInterfaceFactory;
use Vendor\ProductReminder\Api\Data\ProductReminderSearchResultsInterfaceFactory;
use Vendor\ProductReminder\Api\ProductReminderRepositoryInterface;
use Vendor\ProductReminder\Model\ResourceModel\ProductReminder as ResourceProductReminder;
use Vendor\ProductReminder\Model\ResourceModel\ProductReminder\CollectionFactory as ProductReminderCollectionFactory;

class ProductReminderRepository implements ProductReminderRepositoryInterface
{
    /**
     * @param ResourceProductReminder $resource
     * @param ProductReminderInterfaceFactory $productReminderFactory
     * @param ProductReminderCollectionFactory $productReminderCollectionFactory
     * @param ProductReminderSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        private readonly ResourceProductReminder $resource,
        private readonly ProductReminderInterfaceFactory $productReminderFactory,
        private readonly ProductReminderCollectionFactory $productReminderCollectionFactory,
        private readonly ProductReminderSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(ProductReminderInterface $productReminder): ProductReminderInterface
    {
        try {
            $this->resource->save($productReminder);
        } catch (\Throwable $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the productReminder: %1',
                $exception->getMessage()
            ));
        }

        return $productReminder;
    }

    /**
     * @inheritDoc
     */
    public function get(int $reminderId): ProductReminderInterface
    {
        $productReminder = $this->productReminderFactory->create();
        $this->resource->load($productReminder, $reminderId);

        if (!$productReminder->getId()) {
            throw new NoSuchEntityException(__('ProductReminder with id "%1" does not exist.', $reminderId));
        }

        return $productReminder;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    ): \Magento\Framework\Api\SearchResultsInterface {
        $collection = $this->productReminderCollectionFactory->create();
        
        $this->collectionProcessor->process($searchCriteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $items = [];

        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(ProductReminderInterface $productReminder): void
    {
        try {
            $productReminderModel = $this->productReminderFactory->create();
            $this->resource->load($productReminderModel, $productReminder->getId());
            $this->resource->delete($productReminderModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ProductReminder: %1',
                $exception->getMessage()
            ));
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $reminderId): void
    {
        $this->delete($this->get($reminderId));
    }
}

