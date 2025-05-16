<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\ProductReminder\Api\Data;

interface ProductReminderSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get ProductReminder list.
     * @return \Vendor\ProductReminder\Api\Data\ProductReminderInterface[]
     */
    public function getItems(): array;

    /**
     * Set id list.
     * @param \Vendor\ProductReminder\Api\Data\ProductReminderInterface[] $items
     * @return $this
     */
    public function setItems(array $items): self;
}

