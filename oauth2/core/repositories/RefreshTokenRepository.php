<?php

namespace longthanhtran\oauth2\core\repositories;

use longthanhtran\oauth2\core\models\RefreshToken;
use longthanhtran\oauth2\services\RefreshTokenService;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{

    protected RefreshTokenService $refreshTokenService;

    public function __construct(RefreshTokenService $refreshTokenService)
    {
        $this->refreshTokenService = $refreshTokenService;
    }

    /**
     * @return RefreshToken
     */
    public function getNewRefreshToken(): RefreshToken
    {
        return new RefreshToken;
    }

    /**
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $this->refreshTokenService->create([
            'id' => $refreshTokenEntity->getIdentifier(),
            'access_token_id' => $refreshTokenEntity->getAccessToken()->getIdentifier(),
            'revoked' => false,
            'expires_at' => $refreshTokenEntity->getExpiryDateTime()
        ]);
    }

    /**
     * @param string $tokenId
     * @return void
     */
    public function revokeRefreshToken($tokenId)
    {
        $this->refreshTokenService->revokeRefreshToken($tokenId);
    }

    /**
     * @param string $tokenId
     * @return bool
     */
    public function isRefreshTokenRevoked($tokenId): bool
    {
        return $this->refreshTokenService->isRefreshTokenRevoked($tokenId);
    }
}