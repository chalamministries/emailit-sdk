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

### Endpoint Coverage

| Area | Endpoint | SDK method |
| --- | --- | --- |
| Emails | /emails (send) | `$client->email()->send()` |
| Emails | /emails/{id} (get) | `$client->email()->get($id)` |
| Emails | /emails/{id} (update) | `$client->email()->update($id, $payload)` |
| Emails | /emails/{id}/cancel | `$client->email()->cancel($id)` |
| Emails | /emails/{id}/retry | `$client->email()->retry($id)` |
| Domains | /domains (create) | `$client->domains()->create($domain)` |
| Domains | /domains/{id} (get) | `$client->domains()->get($id)` |
| Domains | /domains/{id} (update) | `$client->domains()->update($id, $payload)` |
| Domains | /domains (list) | `$client->domains()->list($perPage, $page, $search)` |
| Domains | /domains/{id} (delete) | `$client->domains()->delete($id)` |
| API Keys | /api-keys (create) | `$client->apiKeys()->create($name, $type, $payload)` |
| API Keys | /api-keys/{id} (get) | `$client->apiKeys()->get($id)` |
| API Keys | /api-keys/{id} (update) | `$client->apiKeys()->update($id, $payload)` |
| API Keys | /api-keys/{id} (delete) | `$client->apiKeys()->delete($id)` |
| API Keys | /api-keys (list) | `$client->apiKeys()->list($perPage, $page, $search)` |
| Audiences | /audiences (create) | `$client->audiences()->create($name, $payload)` |
| Audiences | /audiences/{id} (get) | `$client->audiences()->get($id)` |
| Audiences | /audiences/{id} (update) | `$client->audiences()->update($id, $payload)` |
| Audiences | /audiences/{id} (delete) | `$client->audiences()->delete($id)` |
| Audiences | /audiences (list) | `$client->audiences()->list($page, $perPage, $search)` |
| Audience Subscribers | /audiences/{id}/subscribers (add) | `$client->audiences()->subscribers($id)->add($email, $payload)` |
| Audience Subscribers | /audiences/{id}/subscribers/{id} (get) | `$client->audiences()->subscribers($id)->get($subscriberId)` |
| Audience Subscribers | /audiences/{id}/subscribers/{id} (update) | `$client->audiences()->subscribers($id)->update($subscriberId, $payload)` |
| Audience Subscribers | /audiences/{id}/subscribers/{id} (delete) | `$client->audiences()->subscribers($id)->delete($subscriberId)` |
| Audience Subscribers | /audiences/{id}/subscribers (list) | `$client->audiences()->subscribers($id)->list($perPage, $page)` |
| Templates | /templates (list) | `$client->templates()->list($perPage, $page, $filters)` |
| Templates | /templates (create) | `$client->templates()->create($payload)` |
| Templates | /templates/{id} (get) | `$client->templates()->get($id)` |
| Templates | /templates/{id} (update) | `$client->templates()->update($id, $payload)` |
| Templates | /templates/{id} (delete) | `$client->templates()->delete($id)` |
| Templates | /templates/{id}/publish | `$client->templates()->publish($id)` |
| Suppressions | /suppressions (list) | `$client->suppressions()->list($perPage, $page)` |
| Suppressions | /suppressions (create) | `$client->suppressions()->create($email, $type, $payload)` |
| Suppressions | /suppressions/{id} (get) | `$client->suppressions()->get($id)` |
| Suppressions | /suppressions/{id} (update) | `$client->suppressions()->update($id, $payload)` |
| Suppressions | /suppressions/{id} (delete) | `$client->suppressions()->delete($id)` |
| Webhooks | /webhooks (list) | `$client->webhooks()->list($perPage, $page)` |
| Webhooks | /webhooks (create) | `$client->webhooks()->create($payload)` |
| Webhooks | /webhooks/{id} (get) | `$client->webhooks()->get($id)` |
| Webhooks | /webhooks/{id} (update) | `$client->webhooks()->update($id, $payload)` |
| Webhooks | /webhooks/{id} (delete) | `$client->webhooks()->delete($id)` |
| Contacts | /contacts (list) | `$client->contacts()->list($perPage, $page)` |
| Contacts | /contacts (create) | `$client->contacts()->create($payload)` |
| Contacts | /contacts/{id} (get) | `$client->contacts()->get($id)` |
| Contacts | /contacts/{id} (update) | `$client->contacts()->update($id, $payload)` |
| Contacts | /contacts/{id} (delete) | `$client->contacts()->delete($id)` |
| Events | /events (list) | `$client->events()->list($perPage, $page, $type)` |
| Events | /events/{id} (get) | `$client->events()->get($id)` |

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

### Template Management

Create and manage reusable templates:

```php
$templates = $client->templates();

// List templates with optional filters
$list = $templates->list(25, 1, [
    'name' => 'Welcome',
    'alias' => 'welcome-email',
    'editor' => 'html',
    'sort' => 'created_at',
    'order' => 'desc',
]);

// Create a template
$created = $templates->create([
    'name' => 'Welcome Email',
    'alias' => 'welcome-email',
    'from' => 'Support <support@company.com>',
    'subject' => 'Welcome to our service!',
    'reply_to' => ['support@company.com'],
    'html' => '<h1>Welcome!</h1><p>Thanks for joining us.</p>',
    'text' => 'Welcome! Thanks for joining us.',
    'editor' => 'html',
]);

$templateId = $created['data']['id'] ?? $created['id'] ?? null;

if ($templateId) {
    // Update template metadata or content
    $templates->update($templateId, [
        'subject' => 'Welcome! Updated version',
    ]);

    // Publish a template version
    $templates->publish($templateId);

    // Retrieve the template details
    $details = $templates->get($templateId);

    // Delete when no longer needed
    $templates->delete($templateId);
}
```

### Suppression Management

Manage suppression entries for unsubscribes, bounces, or complaints:

```php
$suppressions = $client->suppressions();

// List suppressions
$list = $suppressions->list(25, 1);

// Create a suppression entry
$created = $suppressions->create('unsubscribed@example.com', 'unsubscribe', [
    'reason' => 'User requested removal',
    'keep_until' => null,
]);

$suppressionId = $created['data']['id'] ?? $created['id'] ?? null;

if ($suppressionId) {
    // Update suppression metadata
    $suppressions->update($suppressionId, [
        'reason' => 'Updated request',
        'keep_until' => '2026-01-01T00:00:00.000000Z',
    ]);

    // Fetch the suppression entry
    $details = $suppressions->get($suppressionId);

    // Delete when no longer needed
    $suppressions->delete($suppressionId);
}
```

### Email Verification

Verify individual addresses or run list verification jobs:

```php
$verifications = $client->emailVerifications();

// Verify a single email address
$verification = $verifications->verify('user@example.com', 'full');

// Create a verification list
$list = $verifications->createList('Marketing List Q1', [
    'user1@example.com',
    'user2@example.com',
    'user3@example.com',
]);

$listId = $list['data']['id'] ?? $list['id'] ?? null;

// List verification lists
$lists = $verifications->listLists(10, 1);

if ($listId) {
    // Fetch list metadata
    $details = $verifications->getList($listId);

    // Fetch verification results
    $results = $verifications->getListResults($listId, 50, 1);

    // Export results (XLSX bytes)
    $xlsx = $verifications->exportList($listId);
    file_put_contents('verification_results.xlsx', $xlsx);
}
```

### Webhook Management

Manage webhook endpoints for event delivery:

```php
$webhooks = $client->webhooks();

// List webhooks
$list = $webhooks->list(10, 1);

// Create a webhook
$created = $webhooks->create([
    'name' => 'Production Webhook',
    'url' => 'https://example.com/webhook',
    'all_events' => false,
    'enabled' => true,
    'events' => ['email.accepted', 'email.delivered', 'email.bounced'],
]);

$webhookId = $created['data']['id'] ?? $created['id'] ?? null;

if ($webhookId) {
    // Update the webhook
    $webhooks->update($webhookId, [
        'name' => 'Updated Webhook',
        'enabled' => false,
        'events' => ['email.accepted', 'email.bounced'],
    ]);

    // Fetch the webhook
    $details = $webhooks->get($webhookId);

    // Delete when no longer needed
    $webhooks->delete($webhookId);
}
```

### Contact Management

Create and manage contacts used in audiences:

```php
$contacts = $client->contacts();

// List contacts
$list = $contacts->list(10, 1);

// Create a contact
$created = $contacts->create([
    'email' => 'john@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'custom_fields' => ['company' => 'Acme'],
    'audiences' => ['aud_123456789'],
    'unsubscribed' => false,
]);

$contactId = $created['data']['id'] ?? $created['id'] ?? null;

if ($contactId) {
    // Update contact metadata
    $contacts->update($contactId, [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'custom_fields' => ['company' => 'NewCorp'],
    ]);

    // Fetch the contact
    $details = $contacts->get($contactId);

    // Delete when no longer needed
    $contacts->delete($contactId);
}
```

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
