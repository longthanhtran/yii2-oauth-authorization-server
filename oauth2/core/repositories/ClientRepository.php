<?php

namespace longthanhtran\oauth2\core\repositories;

use longthanhtran\oauth2\core\models\Client;
use longthanhtran\oauth2\models\Client as ClientModel;
use longthanhtran\oauth2\services\ClientService;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{

    /**
     * @var ClientService
     */
    protected ClientService $clientService;

    public function __construct()
    {
        $this->clientService = new ClientService();
    }

    /**
     * @param string $clientIdentifier
     * @return Client|null
     */
    public function getClientEntity($clientIdentifier): ?Client
    {
        $record = $this->clientService->findActive($clientIdentifier);

        if (! $record) {
            return null;
        }

        // Get a core's Client
        return new Client(
            $clientIdentifier,
            $record->name,
            $record->redirect,
            $record->confidential(),
            $record->provider
        );
    }

    /**
     * @param string $clientIdentifier
     * @param string|null $clientSecret
     * @param string|null $grantType
     * @return bool
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $activeClient = $this->clientService->findActive($clientIdentifier);

        if (! $activeClient || $this->handleGrant($activeClient, $grantType)) {
            return false;
        }

        return ! $activeClient->confidential() ||
            $this->verifySecret($clientSecret, $activeClient->secret);
    }

    /**
     * @param ClientModel $client
     * @param string $grantType
     * @return bool
     */
    protected function handleGrant(ClientModel $client, string $grantType): bool
    {
        if (is_array($client->grant_types) && ! in_array($grantType, $client->grant_types)) {
            return false;
        }

        switch ($grantType) {
            case 'authorization_code':
                return !$client->firstParty();
            case 'personal_access':
                return $client->personal_access_client && $client->confidential();
            case 'password':
                return $client->password_client;
            case 'client_credentials':
                return $client->confidential();
            default:
                return true;
        }
    }

    protected function verifySecret($clientSecret, $storedHash): bool
    {
        return password_verify($clientSecret, $storedHash);
    }
}
