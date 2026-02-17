<?php

namespace EmailIt;

class ContactManager
{
    private EmailItClient $client;

    public function __construct(EmailItClient $client)
    {
        $this->client = $client;
    }

    /**
     * List contacts.
     */
    public function list(int $perPage = 25, int $page = 1): array
    {
        $params = [
            'page' => $page,
            'limit' => $perPage,
        ];

        return $this->client->request('GET', '/contacts', $params);
    }

    /**
     * Retrieve a contact by ID.
     */
    public function get(string $contactId): array
    {
        return $this->client->request('GET', "/contacts/{$contactId}");
    }

    /**
     * Create a new contact.
     *
     * Supported attributes: email, first_name, last_name, custom_fields, audiences, unsubscribed
     *
     * @throws EmailItException
     */
    public function create(array $attributes): array
    {
        $payload = $this->normalizeAttributes($attributes, true);

        return $this->client->request('POST', '/contacts', $payload);
    }

    /**
     * Update an existing contact.
     *
     * Supported attributes: first_name, last_name, custom_fields, audiences, unsubscribed
     *
     * @throws EmailItException
     */
    public function update(string $contactId, array $attributes): array
    {
        $payload = $this->normalizeAttributes($attributes, false);

        if (empty($payload)) {
            throw new EmailItException('Contact update payload cannot be empty');
        }

        return $this->client->request('POST', "/contacts/{$contactId}", $payload);
    }

    /**
     * Delete a contact.
     */
    public function delete(string $contactId): bool
    {
        $this->client->request('DELETE', "/contacts/{$contactId}");

        return true;
    }

    private function normalizeAttributes(array $attributes, bool $requireEmail): array
    {
        $payload = [];

        foreach ($attributes as $key => $value) {
            switch ($key) {
                case 'email':
                case 'first_name':
                case 'last_name':
                    $value = trim((string) $value);
                    if ($value === '') {
                        if ($requireEmail && $key === 'email') {
                            throw new EmailItException('Contact email cannot be empty');
                        }
                        continue 2;
                    }
                    $payload[$key] = $value;
                    break;
                case 'unsubscribed':
                    $payload[$key] = (bool) $value;
                    break;
                case 'custom_fields':
                case 'audiences':
                    if ($value === null) {
                        continue 2;
                    }
                    $payload[$key] = $value;
                    break;
                default:
                    $payload[$key] = $value;
            }
        }

        if ($requireEmail && !isset($payload['email'])) {
            throw new EmailItException('Contact email cannot be empty');
        }

        return $payload;
    }
}
