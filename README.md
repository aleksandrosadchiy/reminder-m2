# Vendor_ProductReminder

## Overview

The **Vendor_ProductReminder** module for Magento 2 enables customers to set reminders for products they are interested in, with notifications sent via email on the specified reminder date. The module includes REST API endpoints for managing reminders, a cron job for sending emails, and an observer to handle product deletion. It also provides admin configuration options to manage module settings.

---

## Features

- **Reminder Management via REST API:**
    - Create reminders for products with a specified reminder date.
    - Retrieve all reminders for a specific customer.
    - Delete reminders by ID.
    - Validates that reminder dates are in the future.

- **Admin Configuration:**
    - Enable/disable the module.
    - Configure the email sender address.
    - Set a default reminder message.

- **Cron Job:**
    - Runs daily to check for reminders due on the current date with "Pending" status.
    - Sends reminder emails using Magento's email template system.
    - Updates reminder status to "Sent" after email dispatch.

- **Observer:**
    - Automatically deletes reminders associated with a product when the product is removed from the catalog.

- **Bonus Features:**
    - Custom ACL to restrict REST API access to authorized customers.
    - Optional email notification when the reminder date is less than a week away.

---

## Installation

1. **Download and Extract Module:**
    - Download the module as a compressed folder (ZIP).
    - Extract the folder and place it in the `app/code/Vendor/ProductReminder` directory of your Magento installation.

2. **Enable the Module:**
   Run the following commands:
   ```bash
   php bin/magento module:enable Vendor_ProductReminder
   php bin/magento setup:upgrade
   php bin/magento setup:di:compile
   php bin/magento cache:clean
   ```

3. **Verify Installation:**
    - Log in to the Magento admin panel.
    - Navigate to **Stores > Configuration > Product Reminder** to confirm the module is installed and enabled.

---

## Configuration

1. **Enable the Module:**
    - Go to **Stores > Configuration > Product Reminder**.
    - Set "Enable Module" to "Yes".

2. **Configure Email Settings:**
    - Set the "Email Sender Address" for reminder emails.
    - Define the "Default Reminder Message" to be used in email templates.

3. **REST API Access:**
    - Ensure proper ACL configurations are set for authorized customer access to the REST API endpoints.

---

## How It Works

1. **Setting a Reminder:**
    - Customers use the REST API endpoint `POST /V1/product-reminder` to set a reminder, providing `customer_id`, `product_id`, and `reminder_date`.
    - The module validates that the `reminder_date` is in the future and saves the reminder in the `product_reminder` table.

2. **Retrieving Reminders:**
    - The `GET /V1/product-reminder/{customer_id}` endpoint returns all reminders for a specific customer, including `id`, `product_id`, `reminder_date`, and `status`.

3. **Deleting a Reminder:**
    - The `DELETE /V1/product-reminder/{id}` endpoint removes a reminder by its ID.

4. **Cron Job for Email Notifications:**
    - A daily cron job checks the `product_reminder` table for reminders where `reminder_date` matches the current date and `status` is "Pending".
    - Sends an email to the customer using the configured email template and updates the reminder status to "Sent".

5. **Product Deletion Handling:**
    - An observer listens for the product deletion event and removes all associated reminders from the `product_reminder` table.

6. **Bonus Notification:**
    - If enabled, a secondary cron job sends a warning email to customers when their reminder date is less than a week away.

---

## REST API Endpoints

### Set Reminder
- **Endpoint:** `POST /V1/product-reminder`
- **Payload:**
  ```json
  {
    "customer_id": 123,
    "product_id": 456,
    "reminder_date": "2025-12-25"
  }
  ```
- **Description:** Creates a new reminder and saves it to the `product_reminder` table.

### Get All Reminders for a Customer
- **Endpoint:** `GET /V1/product-reminder/{customer_id}`
- **Response Example:**
  ```json
  [
    {
      "id": 1,
      "product_id": 456,
      "reminder_date": "2025-12-25",
      "status": "Pending"
    }
  ]
  ```
- **Description:** Retrieves all reminders for the specified customer.

### Delete a Reminder
- **Endpoint:** `DELETE /V1/product-reminder/{id}`
- **Description:** Deletes the reminder with the specified ID.

---

## Troubleshooting

1. **Module Not Visible:**
    - Verify the module is enabled:
      ```bash
      php bin/magento module:status
      ```
    - Clear cache:
      ```bash
      php bin/magento cache:clean
      ```

2. **REST API Issues:**
    - Ensure ACL configurations are correctly set for customer authorization.
    - Verify the REST API endpoints are accessible and properly registered.

3. **Emails Not Sending:**
    - Check the cron job configuration in `crontab.xml` and ensure Magento’s cron is running.
    - Verify the email sender address and template settings in **Stores > Configuration > Product Reminder**.

4. **Reminders Not Deleted on Product Removal:**
    - Confirm the observer is registered for the `catalog_product_delete_after` event.
    - Check the observer logic in the module’s codebase.

---

## Development Approach

- **Module Structure:** Followed Magento 2’s standard directory structure with `registration.php`, `module.xml`, and appropriate namespaces (`Vendor\ProductReminder`).
- **Database:** Created the `product_reminder` table using a `db_schema.xml` file for declarative schema management.
- **REST API:** Implemented endpoints using Magento’s API framework, with proper validation and ACL integration.
- **Cron Job:** Configured in `crontab.xml` to run daily, leveraging Magento’s email template system for notifications.
- **Observer:** Used event-observer pattern to handle product deletion, ensuring data integrity.
- **Code Quality:** Adhered to Magento 2 coding standards, used Dependency Injection, and included PHPDoc comments for clarity.
- **Bonus Features:** Added ACL for API security and a week-prior notification feature for enhanced user experience.

---

## License

All rights reserved.

---
