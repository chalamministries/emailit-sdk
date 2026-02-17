<?php

namespace EmailIt;

class WebhookManager
{
    private EmailItClient $client;

    public function __construct(EmailItClient $client)
    {
        $this->client = $client;
    }

    /**
     * List webhooks.
     */
    public function list(int $perPage = 25, int $page = 1): array
    {
        $params = [
            'page' => $page,
            'limit' => $perPage,
        ];

        return $this->client->request('GET', '/webhooks', $params);
    }

    /**
     * Retrieve a webhook by ID.
     */
    public function get(string $webhookId): array
    {
        return $this->client->request('GET', "/webhooks/{$webhookId}");
    }

    /**
     * Create a new webhook.
     *
     * Supported attributes: name, url, all_events, enabled, events
     *
     * @throws EmailItException
     */
    public function create(array $attributes): array
    {
        $payload = $this->normalizeAttributes($attributes, true);

        return $this->client->request('POST', '/webhooks', $payload);
    }

    /**
     * Update an existing webhook.
     *
     * Supported attributes: name, url, all_events, enabled, events
     *
     * @throws EmailItException
     */
    public function update(string $webhookId, array $attributes): array
    {
        $payload = $this->normalizeAttributes($attributes, false);

        if (empty($payload)) {
            throw new EmailItException('Webhook update payload cannot be empty');
        }

        return $this->client->request('POST', "/webhooks/{$webhookId}", $payload);
    }

    /**
     * Delete a webhook.
     */
    public function delete(string $webhookId): bool
    {
        $this->client->request('DELETE', "/webhooks/{$webhookId}");

        return true;
    }

    private function normalizeAttributes(array $attributes, bool $requireName): array
    {
        $payload = [];

        foreach ($attributes as $key => $value) {
            switch ($key) {
                case 'name':
                case 'url':
                    $value = trim((string) $value);
                    if ($value === '') {
                        if ($requireName && $key === 'name') {
                            throw new EmailItException('Webhook name cannot be empty');
                        }
                        continue 2;
                    }
                    $payload[$key] = $value;
                    break;
                case 'all_events':
                case 'enabled':
                    $payload[$key] = (bool) $value;
                    break;
                case 'events':
                    if ($value === null) {
                        continue 2;
                    }
                    $payload[$key] = $value;
                    break;
                default:
                    $payload[$key] = $value;
            }
        }

        if ($requireName && !isset($payload['name'])) {
            throw new EmailItException('Webhook name cannot be empty');
        }

        if ($requireName && (!isset($payload['url']) || trim((string) $payload['url']) === '')) {
            throw new EmailItException('Webhook URL cannot be empty');
        }

        return $payload;
    }
}
