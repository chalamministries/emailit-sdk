**EmailIt SDK for PHP Documentation**

**Table of Contents**

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Getting Started](#getting-started)
4. [Email Client](#email-client)
   - [EmailBuilder](#emailbuilder)
5. [Managers](#managers)
   - [AudienceManager](#audience-manager)
   - [CredentialManager](#credential-manager)
   - [SendingDomainManager](#sending-domain-manager)
   - [EventManager](#event-manager)
6. [Exceptions](#exceptions)

## Introduction

The EmailIt SDK for PHP is a client library that enables you to interact with the EmailIt API, allowing you to send emails, manage audiences, credentials, sending domains, and events. This documentation will guide you through the usage of the SDK and its features.

## Installation

### Composer

The recommended way to install the EmailIt SDK for PHP is by using Composer, the package manager for PHP. Run the following command to install the SDK:

```bash
composer require emailit/emailit-sdk
```

### Manual Installation

If you prefer not to use Composer, you can manually install the SDK by downloading the latest release from the [GitHub repository](https://github.com/chalamministries/emailit-sdk) and copying the contents of the `src` folder to your project.

After copying the files, you can use the `autoload.php` file to autoload the classes in your project. Include the `autoload.php` file at the beginning of your script:

```php
require_once 'path/to/emailit-sdk/src/autoload.php';
```

## Usage

### Composer Autoload

If you installed the SDK using Composer, you can use the following code to initialize the `EmailItClient` with your API key and base URL (default is `https://api.emailit.com/v1`):

```php
require_once 'vendor/autoload.php';

use EmailIt\EmailItClient;
use EmailIt\EmailBuilder;

$apiKey = 'your_api_key_here';
$baseUrl = 'https://api.emailit.com/v1';

$client = new EmailItClient($apiKey, $baseUrl);
```

### Manual Autoload

If you manually installed the SDK and are using the `autoload.php` file, you can use the following code to initialize the `EmailItClient` with your API key and base URL (default is `https://api.emailit.com/v1`):

```php
require_once 'path/to/emailit-sdk/src/autoload.php';

use EmailIt\EmailItClient;
use EmailIt\EmailBuilder;

$apiKey = 'your_api_key_here';
$baseUrl = 'https://api.emailit.com/v1';

$client = new EmailItClient($apiKey, $baseUrl);
```

## Email Client

The `EmailItClient` class is the main entry point for interacting with the EmailIt API. It provides methods to create email messages, manage audiences, credentials, sending domains, and events.

### EmailBuilder

The `EmailBuilder` class allows you to build email messages with ease. It supports setting the `from`, `to`, `reply_to`, `subject`, `html`, and `text` content, as well as adding attachments and custom headers.

**Example usage:**

```php
$email = $client->email();
$email->from('sender@example.com')
      ->to('recipient@example.com')
      ->replyTo('reply@example.com')
      ->subject('Test Email')
      ->html('<h1>Hello, World!</h1>')
      ->text('This is a test email.')
      ->addAttachment('path/to/attachment.txt', file_get_contents('path/to/attachment.txt'), 'text/plain')
      ->addHeader('X-Custom-Header', 'Custom Value')
      ->send();
```

## Managers

The SDK provides several manager classes to handle specific aspects of the EmailIt API:

### AudienceManager

The `AudienceManager` class allows you to manage audiences, including listing, creating, retrieving, updating, and deleting audiences, as well as subscribing email addresses to an audience.

**Example usage:**

```php
$audienceManager = $client->audiences();

// List audiences
$audienceList = $audienceManager->list();

// Create a new audience
$audienceManager->create('New Audience');

// Subscribe an email address to an audience
$audienceManager->subscribe('audience_token', 'subscriber@example.com', 'First', 'Last', ['custom_field' => 'custom_value']);
```

### CredentialManager

The `CredentialManager` class enables you to manage credentials, such as listing, creating, retrieving, updating, and deleting credentials.

**Example usage:**

```php
$credentialManager = $client->credentials();

// List credentials
$credentialList = $credentialManager->list();

// Create a new credential
$credentialManager->create('New Credential', 'smtp');
```

### SendingDomainManager

The `SendingDomainManager` class allows you to manage sending domains, including listing, creating, retrieving, checking DNS records, and deleting sending domains.

**Example usage:**

```php
$sendingDomainManager = $client->sendingDomains();

// List sending domains
$sendingDomainList = $sendingDomainManager->list();

// Create a new sending domain
$sendingDomainManager->create('example.com');

// Check DNS records of a sending domain
$sendingDomainManager->checkDns('sending_domain_id');
```

### EventManager

The `EventManager` class enables you to manage events, such as listing and retrieving events, and getting available event types.

**Example usage:**

```php
$eventManager = $client->events();

// List events
$eventList = $eventManager->list();

// Retrieve an event by ID
$event = $eventManager->get('event_id');

// Get available event types
$eventTypes = EventManager::getEventTypes();
```

## Exceptions

The `EmailItException` class is a custom exception thrown by the SDK when an error occurs while interacting with the EmailIt API. You can catch this exception to handle errors gracefully.

**Example usage:**

```php
try {
    // Perform an API request that may throw an EmailItException
} catch (EmailItException $e) {
    echo 'API Error: ' . $e->getMessage() . ' (Status Code: ' . $e->getCode() . ')';
}
```

## Contributing

Contributions are welcome! If you encounter any issues or have suggestions for improvement, please submit an issue or pull request on the [GitHub repository](https://github.com/yourusername/emailit-sdk).

## License

The EmailIt SDK for PHP is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.
