<?php

namespace longthanhtran\oauth2\models;

use app\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string id
 * @property integer user_id
 * @property integer client_id
 * @property string scopes
 * @property boolean revoked
 */
class AuthCode extends ActiveRecord
{
    public static function primaryKey(): array
    {
        return ['id'];
    }

    public static function tableName(): string
    {
        return '{{%auth_codes}}';
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}