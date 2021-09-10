<?php

namespace longthanhtran\oauth2\models;

use DateTimeImmutable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property boolean revoked
 * @property string id
 * @property string access_token_id
 * @property DateTimeImmutable expires_at
 */
class RefreshToken extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%refresh_tokens}}';
    }

    public function getAccessToken(): ActiveQuery
    {
        return $this->hasOne(Token::class, ['id' => 'access_token_id']);
    }

}