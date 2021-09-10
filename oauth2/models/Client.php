<?php

namespace longthanhtran\oauth2\models;

use app\models\User;
use yii\db\ActiveRecord;

/**
 * @property array grant_types
 * @property string secret
 * @property string name
 * @property string redirect
 * @property string provider
 * @property boolean revoked
 * @property boolean password_client
 * @property boolean personal_access_client
 */
class Client extends ActiveRecord
{
    public array $grant_types = [];

    public static function tableName(): string
    {
        return '{{%clients}}';
    }

    /**
     * Tell if the client has secret
     * @return bool
     */
    public function confidential(): bool
    {
        return ! empty($this->secret);
    }

    /**
     * Is this a "first party" client?
     * @return bool
     */
    public function firstParty(): bool
    {
        return $this->personal_access_client ||
            $this->password_client;
    }

    public function getUser()
    {
        $this->hasOne(User::class, ['id' => 'user_id']);
    }

}