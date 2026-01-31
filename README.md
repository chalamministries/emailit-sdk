# EmailIt SDK for PHP

A PHP SDK for interacting with the EmailIt API, allowing you to send emails, manage audiences, credentials, sending domains, and events.

## Table of Contents

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Basic Usage](#basic-usage)
4. [Features](#features)
5. [Examples](#examples)
6. [Error Handling](#error-handling)

## Requirements

- PHP 7.3 or higher
- cURL extension enabled

## Installation

You have two options for installing the EmailIt SDK:

### Option 1: Using Composer (Recommended)

```bash
composer require emailit/emailit-sdk
```

Then in your PHP script:

```php
require 'vendor/autoload.php';

use EmailIt\EmailItClient;

$client = new EmailItClient('your_api_key');
```

### Option 2: Manual Installation

1. Download the latest release from GitHub
2. Include the autoloader in your PHP script:

```php
require_once 'path/to/emailit-sdk/autoload.php';

use EmailIt\EmailItClient;

$client = new EmailItClient('your_api_key');
```

## Basic Usage

### Initialize the Client

```php
use EmailIt\EmailItClient;

$client = new EmailItClient('your_api_key');
```

### Send an Email

```php
$email = $client->email();

$result = $email->from('Sender <sender@example.com>')
      ->to(['recipient1@example.com', 'recipient2@example.com'])
      ->cc('marketing@example.com')
      ->bcc('finance@example.com')
      ->replyTo('reply@example.com')
      ->subject('Test Email')
      ->html('<h1>Hello, World!</h1>')
      ->text('This is a test email.')
      ->tracking(true, true)
      ->scheduledAt('2026-02-01T12:00:00Z')
      ->send();
```

> **Tip:** The value returned from `send()` includes the email identifier (for example, `id` or `uuid`). Pass that identifier to `get()`, `update()`, `cancel()`, and `retry()` to manage the message lifecycle.

## Features

### Email Management

The EmailBuilder class provides a fluent interface for creating and sending emails:

```php
$email = $client->email();

$result = $email->from('Sender <sender@example.com>')
      ->to(['recipient@example.com', 'customer@example.com'])
      ->addCc('manager@example.com')
      ->addBcc('auditor@example.com')
      ->replyTo('reply@example.com')
      ->subject('Test Email')
      ->html('<h1>Hello, World!</h1>')
      ->text('This is a test email.')
      ->addAttachment('file.pdf', $fileContent, 'application/pdf')
      ->addHeader('X-Custom-Header', 'Value')
      ->tracking(true, true)
      ->addTag('welcome-series')
      ->addMetadata('customer_id', 987654321)
      ->send();

$emailId = $result['id'] ?? $result['uuid'] ?? null;

if ($emailId !== null) {
    // Update the scheduled time
    $client->email()->update($emailId, [
        'scheduled_at' => '2026-02-01T15:00:00Z'
    ]);

    // Cancel or retry as needed
    $client->email()->cancel($emailId);
    $client->email()->retry($emailId);

    // Fetch the latest status
    $details = $client->email()->get($emailId);
}
```

### Audience Management

Manage your email audiences:

```php
$audiences = $client->audiences();

// List audiences (page 1, 25 per page, optional keyword search)
$list = $audiences->list(1, 25, 'Newsletter');

// Create an audience with an optional description
$newAudience = $audiences->create('New Newsletter', [
    'description' => 'Subscribers to the primary product updates.',
]);

$audienceId = $newAudience['id'] ?? $newAudience['uuid'] ?? null;

if ($audienceId) {
    $subscribers = $audiences->subscribers($audienceId);
    // Alternatively: $subscribers = $client->audienceSubscribers($audienceId);

    // Add a subscriber
    $subscriber = $subscribers->add('user@example.com', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'custom_fields' => ['interests' => 'technology'],
    ]);

    // Update subscription preferences
    $subscribers->update($subscriber['id'], ['subscribed' => true]);

    // Remove a subscriber
    $subscribers->delete($subscriber['id']);
}
```

> **Note:** `AudienceManager::subscribe()` remains available for backward compatibility but now proxies to the new subscriber endpoints.

### API Key Management

Manage your API keys:

```php
$apiKeys = $client->apiKeys();

// List API keys
$list = $apiKeys->list(25, 1, 'Primary');

// Create API key
$newApiKey = $apiKeys->create('Primary Sending Key', 'sending', [
    'sending_domain_id' => 1234567890
]);

// Update API key
$apiKeys->update($newApiKey['id'], ['name' => 'Updated API Key']);

// Delete API key
$apiKeys->delete($newApiKey['id']);
```

> **Note:** `$client->credentials()` remains available for backwards compatibility but is deprecated in favor of `$client->apiKeys()`.

### Domain Management

Manage your domains:

```php
$domains = $client->domains();

// List domains
$list = $domains->list(25, 1, 'example.com');

// Create domain
$newDomain = $domains->create('emails.example.com');

// Verify DNS records
$verification = $domains->verify($newDomain['id']);
```

> **Note:** `$client->sendingDomains()` remains available for backwards compatibility but is deprecated in favor of `$client->domains()`.

### Event Management

Track email-related events:

```php
$events = $client->events();

// List events
$list = $events->list(25, 1, 'email.delivery.sent');

// Get specific event
$event = $events->get('event_id');
```

## Error Handling

The SDK uses the `EmailItException` class for error handling:

```php
use EmailIt\EmailItException;

try {
    $result = $client->email()
        ->from('sender@example.com')
        ->to('recipient@example.com')
        ->subject('Test')
        ->html('<p>Content</p>')
        ->send();
} catch (EmailItException $e) {
    echo 'Error: ' . $e->getMessage();
    echo 'Code: ' . $e->getCode();
}
```
