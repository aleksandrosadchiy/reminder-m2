<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vendor\ProductReminder\Cron;

class ReminderSenderJob
{
    public const EMAIL_TEMPLATE_ID = 'product_reminder_email_template';

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Vendor\ProductReminder\Api\ProductReminderManagementInterface $productReminderManagement
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Vendor\ProductReminder\Api\ProductReminderRepositoryInterface $reminderRepository
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Vendor\ProductReminder\Api\ProductReminderConfigInterface $config
     */
    public function __construct(
        private readonly \Psr\Log\LoggerInterface $logger,
        private readonly \Vendor\ProductReminder\Api\ProductReminderManagementInterface $productReminderManagement,
        private readonly \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        private readonly \Vendor\ProductReminder\Api\ProductReminderRepositoryInterface $reminderRepository,
        private readonly \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        private readonly \Vendor\ProductReminder\Api\ProductReminderConfigInterface $config
    ) {
    }

    /**
     * @return void
     */
    public function executeToday(): void
    {
        $this->processReminders((new \DateTime())->format('Y-m-d'), 'Today');
    }

    /**
     * @return void
     */
    public function executeUpcoming(): void
    {
        $this->processReminders((new \DateTime())->modify('+7 days')->format('Y-m-d'), 'Upcoming');
    }

    /**
     * @param string $date
     * @param string $logPrefix
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     */
    private function processReminders(string $date, string $logPrefix): void
    {
        if (!$this->config->isEnabled()) {
            $this->logger->info("Reminder sending skipped: module disabled in configuration.");
            return;
        }

        $reminders = $this->productReminderManagement->getReminderByDate($date);

        foreach ($reminders as $reminder) {
            try {
                $customerId = $reminder->getCustomerId();
                $customer = $this->customerRepository->getById((int) $customerId);
                $email = $customer->getEmail();
            } catch (\Exception $e) {
                $this->logger->error("Error during $logPrefix reminder '%1' sending: " . $e->getMessage());
                continue;
            }

            $transport = $this->transportBuilder
                ->setTemplateIdentifier(self::EMAIL_TEMPLATE_ID)
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => 1
                ])->setTemplateVars([
                    'reminder' => $reminder,
                    'customer' => ['name' => $customer->getFirstname()],
                    'default_message' => $this->config->getDefaultReminderMessage()
                ])->setFrom([
                    'email' => $this->config->getEmailSender(),
                    'name' => 'Store Reminder'
                ])
                ->addTo($email)
                ->getTransport();

            $transport->sendMessage();
            $this->logger->info("$logPrefix reminder email sent to: $email");

            $reminder->setStatus(\Vendor\ProductReminder\Api\Data\ProductReminderInterface::STATUS_SENT);
            $this->reminderRepository->save($reminder);
        }

        $this->logger->info("Cronjob ReminderSender {$logPrefix} is executed.");
    }
}
