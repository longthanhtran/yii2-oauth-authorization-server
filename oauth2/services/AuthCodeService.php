<?php

namespace longthanhtran\oauth2\services;

use longthanhtran\oauth2\models\AuthCode;
use longthanhtran\oauth2\models\Client;

class AuthCodeService
{

    /**
     * @param array $attributes
     */
    public function create(array $attributes)
    {
        $authCodeModel = new AuthCode();
        $authCodeModel->id = $attributes['id'];
        $authCodeModel->user_id = $attributes['user_id'];
        $authCodeModel->client_id = Client::findOne(['name' => $attributes['client_id']])->id;
        $authCodeModel->scopes = $attributes['scopes'];
        $authCodeModel->save();
    }

    /**
     * @param string $codeId
     */
    public function revoke(string $codeId)
    {
        if ( $authCode = AuthCode::findOne(['id' => $codeId])) {
            $authCode->revoked = true;
            $authCode->save();
        }
    }

    public function isRevoked(string $codeId): bool
    {
        if ($authCode = AuthCode::findOne(['id' => $codeId])) {
            return $authCode->revoked == 1;
        }
        return true;
    }
}