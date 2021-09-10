<?php

namespace longthanhtran\oauth2\core\repositories;

use longthanhtran\oauth2\core\models\Scope;
use longthanhtran\oauth2\services\ScopeService;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeRepository implements ScopeRepositoryInterface
{

    protected ScopeService $scopeService;

    public function __construct(ScopeService $scopeService)
    {
        $this->scopeService = $scopeService;
    }

    /**
     * @param string $identifier
     * @return Scope|void
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        if ($this->scopeService->hasScopes($identifier)) {
            return new Scope($identifier);
        }
    }

    /**
     * @param array $scopes
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @param null $userIdentifier
     * @return array
     */
    public function finalizeScopes(array $scopes,
                                   $grantType,
                                   ClientEntityInterface $clientEntity,
                                   $userIdentifier = null): array
    {
        if (! in_array($grantType, ['password', 'personal_access', 'client_credentials'])) {
            $scopes = array_filter($scopes, function($scope) {
                return trim($scope->getIdentifier()) === '*';
            });
        }
        return array_filter($scopes, function ($scope) {
            return $this->scopeService->hasScopes($scope->getIdentifier());
        });

    }
}