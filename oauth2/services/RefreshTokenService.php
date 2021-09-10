<?php

namespace longthanhtran\oauth2\services;

use longthanhtran\oauth2\models\RefreshToken;

class RefreshTokenService
{

    /**
     * @param string $tokenId
     * @return void
     */
    public function revokeRefreshToken(string $tokenId)
    {
        $refreshToken = RefreshToken::findOne(['id' => $tokenId]);
        if (!empty($refreshToken)) {
            $refreshToken->revoked = true;
            $refreshToken->save();
        }
    }

    /**
     * @param string $tokenId
     * @return bool
     */
    public function isRefreshTokenRevoked(string $tokenId): bool
    {
        if ($refreshToken = RefreshToken::findOne(['id' => $tokenId])) {
            return $refreshToken->revoked;
        }

        return true;
    }

    public function create(array $attributes)
    {
        $refreshToken = new RefreshToken();
        $refreshToken->id = $attributes['id'];
        $refreshToken->access_token_id = $attributes['access_token_id'];
        $refreshToken->revoked = $attributes['revoked'];
        $refreshToken->expires_at = date('Y-m-d H:i:s', $attributes['expires_at']->getTimeStamp());
        $refreshToken->save();
    }
}