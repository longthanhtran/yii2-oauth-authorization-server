<?php

namespace longthanhtran\oauth2\core\models;

use League\oauth2\Server\Entities\AccessTokenEntityInterface;
use League\oauth2\Server\Entities\ClientEntityInterface;
use League\oauth2\Server\Entities\Traits\AccessTokenTrait;
use League\oauth2\Server\Entities\Traits\EntityTrait;
use League\oauth2\Server\Entities\Traits\TokenEntityTrait;

class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait, EntityTrait, TokenEntityTrait;

    /**
     * @param ClientEntityInterface $userIdentifier
     * @param array $scopes
     * @param ClientEntityInterface $client
     * @return void
     */
    public function __construct(ClientEntityInterface $userIdentifier,
                                array $scopes,
                                ClientEntityInterface $client)
    {
        $this->setUserIdentifier($userIdentifier->getIdentifier());
        foreach ($scopes as $scope) {
            $this->addScope($scope);
        }
        $this->setClient($client);
    }

}