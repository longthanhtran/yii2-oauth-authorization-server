<?php

namespace longthanhtran\oauth2\commands;

use longthanhtran\oauth2\models\AuthCode;
use longthanhtran\oauth2\models\Token;
use Carbon\Carbon;
use yii\console\Controller;

class TokenController extends Controller
{

    /**
     * Purge expired tokens and authorization codes
     */
    public function actionPurgeExpiredTokens()
    {
        $now = Carbon::now();

        $this->cleanUpExpiredTokens($now);

        $this->cleanUpExpiredAuthorizationCodes($now);
    }

    /**
     * Purge expired tokens
     * @param Carbon $now
     */
    protected function cleanUpExpiredTokens(Carbon $now): void
    {
        $totalPending = Token::find()->where(['<', 'expires_at', $now])->count();
        printf(">> We'll delete %s expired tokens which are older than %s\n", $totalPending, $now);
        Token::deleteAll(['<', 'expires_at', $now]);
    }

    /**
     * Purge expired Authorization Code (revoked = 1 OR older than 10 minutes)
     * @param Carbon $now
     */
    protected function cleanUpExpiredAuthorizationCodes(Carbon $now): void
    {
        $tenMinutesAgo = $now->subMinutes(10);
        $query = [
            'or',
            ['revoked' => 1],
            ['<', 'created_at', $tenMinutesAgo],
        ];
        $totalPendingAuthCode = AuthCode::find()->where($query)->count();
        printf(">> We'll delete %s expired authorization code which are older than %s\n", $totalPendingAuthCode, $now);
        AuthCode::deleteAll($query);
    }

}