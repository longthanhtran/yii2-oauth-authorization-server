<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m210819_042043_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'username' => $this->string(255),
            'remember_me' => $this->boolean(),
            'hash_password' => $this->string(),
            'access_token' => $this->string(128),
            'last_login' => $this->timestamp(),
            'created_at' => $this->timestamp()->append(
                'DEFAULT CURRENT_TIMESTAMP'
            ),
            'updated_at' => $this->timestamp()->append(
                'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            )
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
