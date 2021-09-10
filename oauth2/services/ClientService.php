<?php

namespace longthanhtran\oauth2\services;

use longthanhtran\oauth2\models\Client;

class ClientService
{

    public function findActive($clientIdentifier): ?Client
    {
        $client = Client::findOne(['name' => $clientIdentifier]);
        return $client && ! $client->revoked ? $client : null;
    }
}