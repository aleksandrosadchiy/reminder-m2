<?php

declare(strict_types=1);

namespace Vendor\ProductReminder\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Vendor\ProductReminder\Api\ProductReminderConfigInterface;

/**
 * Configuration model for the Product Reminder module.
 * Provides access to configuration settings defined in admin panel.
 */
class ProductReminderConfig implements ProductReminderConfigInterface
{
    public const XML_PATH_ENABLE = 'product_reminder/general/enable';
    public const XML_PATH_EMAIL_SENDER = 'product_reminder/general/email_sender';
    public const XML_PATH_DEFAULT_MESSAGE = 'product_reminder/general/default_reminder_message';

    /**
     * @param ScopeConfigInterface $scopeConfig Magento scope config to retrieve system values.
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {}

    /**
     * Check if the product reminder feature is enabled in configuration.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve the configured email sender address for product reminders.
     *
     * @return string
     */
    public function getEmailSender(): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_SENDER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve the default reminder message set in configuration.
     *
     * @return string
     */
    public function getDefaultReminderMessage(): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_MESSAGE,
            ScopeInterface::SCOPE_STORE
        );
    }
}