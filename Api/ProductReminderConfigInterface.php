<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\ProductReminder\Api;

/**
 * Interface for accessing product reminder configuration settings.
 */
interface ProductReminderConfigInterface
{
    /**
     * Check if the product reminder functionality is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Get the configured email sender address.
     *
     * @return string
     */
    public function getEmailSender(): string;

    /**
     * Get the default reminder message.
     *
     * @return string
     */
    public function getDefaultReminderMessage(): string;
}