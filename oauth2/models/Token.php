<?php

namespace longthanhtran\oauth2\models;

use app\models\User;
use DateTimeImmutable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string id
 * @property boolean revoked
 * @property array scopes
 * @property integer user_id
 * @property integer client_id
 * @property Client client
 * @property DateTimeImmutable expires_at
 */
class Token extends ActiveRecord
{
    public static function primaryKey(): array
    {
        return ['id'];
    }

    public static function tableName(): string
    {
        return '{{%access_tokens}}';
    }

    public function setRevoke(bool $true)
    {
        $this->revoked = $true;
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getClient(): ActiveQuery
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }
}