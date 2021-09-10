<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m210830_041002_create_clients_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%clients}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'user_id' => $this->bigInteger()->unsigned()->null()->unique(),
            'name' => $this->string(),
            'secret' => $this->string(100)->null(),
            'provider' => $this->string()->null(),
            'redirect' => $this->string(),
            'personal_access_client' => $this->boolean(),
            'revoked' => $this->boolean(),
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
        $this->dropTable('{{%clients}}');
    }
}
