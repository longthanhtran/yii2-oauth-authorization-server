<?php

namespace longthanhtran\oauth2\services;

use longthanhtran\oauth2\core\repositories\FormatScopesForStorage;
use longthanhtran\oauth2\models\Client;
use longthanhtran\oauth2\models\Token;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

class TokenService
{
    use FormatScopesForStorage;

    /**
     * @param string $tokenId
     * @return void
     */
    public function revokeAccessToken(string $tokenId)
    {
        $token = Token::findOne(['id' => $tokenId]);
        if (!empty($token)) {
            $token->setRevoke(true);
            $token->save();
        }
    }

    /**
     * @param string $tokenId
     * @return bool
     */
    public function isAccessTokenRevoked(string $tokenId): bool
    {
        if ($token = Token::findOne(['id' => $tokenId])) {
            return $token->revoked;
        }
        return true;
    }

    public function create(AccessTokenEntityInterface $attributes)
    {
        $token = new Token();

        $token->id = $attributes->getIdentifier();
        $token->link('client', Client::findOne(['name' => $attributes->getClient()->getIdentifier()]));
        $token->scopes = $this->scopesToArray($attributes->getScopes());
        $token->revoked = false;
        $token->expires_at = date('Y-m-d H:i:s', $attributes->getExpiryDateTime()->getTimeStamp());
        $token->save();
    }
}