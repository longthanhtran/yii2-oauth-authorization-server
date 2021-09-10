<?php

namespace longthanhtran\oauth2\models;

use yii\db\ActiveRecord;

class Scope extends ActiveRecord
{

    public static function tableName(): string
    {
        return '{{%scopes}}';
    }

}