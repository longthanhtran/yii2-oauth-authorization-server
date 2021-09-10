<?php

namespace longthanhtran\oauth2\core\repositories;

use longthanhtran\oauth2\core\models\AccessToken;
use longthanhtran\oauth2\services\TokenService;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use yii\base\Event;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    use FormatScopesForStorage;

    /**
     * @var Event
     */
    protected Event $events;

    /**
     * @var TokenService
     */
    protected TokenService $tokenService;

    public function __construct(TokenService $tokenService, Event $event)
    {
        $this->events = $event;
        $this->tokenService = $tokenService;
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