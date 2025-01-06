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
$email->from('sender@example.com')
      ->to('recipient@example.com')
      ->replyTo('reply@example.com')
      ->subject('Test Email')
      ->html('<h1>Hello, World!</h1>')
      ->text('This is a test email.')
      ->send();
```

## Features

### Email Management

The EmailBuilder class provides a fluent interface for creating and sending emails:

```php
$email = $client->email();
$email->from('sender@example.com')
      ->to('recipient@example.com')
      ->replyTo('reply@example.com')
      ->subject('Test Email')
      ->html('<h1>Hello, World!</h1>')
      ->text('This is a test email.')
      ->addAttachment('file.pdf', $fileContent, 'application/pdf')
      ->addHeader('X-Custom-Header', 'Value')
      ->send();
```

### Audience Management

Manage your email audiences:

```php
$audiences = $client->audiences();

// List audiences
$list = $audiences->list(25, 1, 'Newsletter');

// Create audience
$newAudience = $audiences->create('New Newsletter');

// Subscribe a user
$audiences->subscribe(
    'audience_token',
    'user@example.com',
    'John',
    'Doe',
    ['interests' => 'technology']
);
```

### Credential Management

Handle SMTP and API credentials:

```php
$credentials = $client->credentials();

// List credentials
$list = $credentials->list(25, 1, null, 'smtp');

// Create credential
$newCredential = $credentials->create('Main SMTP', 'smtp');
```

### Sending Domain Management

Manage your sending domains:

```php
$domains = $client->sendingDomains();

// List domains
$list = $domains->list(25, 1, 'example.com');

// Create domain
$newDomain = $domains->create('emails.example.com');

// Check DNS records
$dnsStatus = $domains->checkDns('domain_id');
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
