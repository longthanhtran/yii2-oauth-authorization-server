<?php

namespace longthanhtran\oauth2\core\repositories;

use longthanhtran\oauth2\core\models\AccessToken;
use longthanhtran\oauth2\services\TokenService;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    use FormatScopesForStorage;

    protected TokenService $tokenService;

    public function __construct()
    {
        $this->tokenService = new TokenService();
    }

    /**
     * @param ClientEntityInterface $clientEntity
     * @param array $scopes
     * @param null $userIdentifier
     * @return AccessToken
     */
    public function getNewToken(ClientEntityInterface $clientEntity,
                                array $scopes,
                                $userIdentifier = null): AccessToken
    {
        return new AccessToken($clientEntity, $scopes, $clientEntity);
    }

    /**
     * @param AccessTokenEntityInterface $accessTokenEntity
     */
    public function persistNewAccessToken(
        AccessTokenEntityInterface $accessTokenEntity)
    {
        $this->tokenService->create($accessTokenEntity);
    }

    /**
     * @param string $tokenId
     * @return void
     */
    public function revokeAccessToken($tokenId)
    {
        $this->tokenService->revokeAccessToken($tokenId);
    }

    public function isAccessTokenRevoked($tokenId): bool
    {
        return $this->tokenService->isAccessTokenRevoked($tokenId);
    }

}
