<?php

namespace longthanhtran\oauth2\core\repositories;

use longthanhtran\oauth2\core\models\AuthCode;
use longthanhtran\oauth2\services\AuthCodeService;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    use FormatScopesForStorage;

    protected AuthCodeService $authCodeService;

    public function __construct()
    {
        $this->authCodeService = new AuthCodeService();
    }

    /**
     * @return AuthCode
     */
    public function getNewAuthCode(): AuthCode
    {
        return new AuthCode;
    }

    /**
     * @param AuthCodeEntityInterface $authCodeEntity
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $this->authCodeService->create([
            'id' => $authCodeEntity->getIdentifier(),
            'user_id' => $authCodeEntity->getUserIdentifier(),
            'client_id' => $authCodeEntity->getClient()->getIdentifier(),
            'scopes' => $this->formatScopesForStorage($authCodeEntity->getScopes()),
            'revoked' => false,
            'expires_at' => $authCodeEntity->getExpiryDateTime(),
        ]);
    }

    /**
     * @param string $codeId
     */
    public function revokeAuthCode($codeId)
    {
        $this->authCodeService->revoke($codeId);
    }

    /**
     * @param string $codeId
     * @return bool
     */
    public function isAuthCodeRevoked($codeId): bool
    {
        return $this->authCodeService->isRevoked($codeId);
    }
}
