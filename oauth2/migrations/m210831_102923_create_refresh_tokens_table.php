<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%refresh_tokens}}`.
 */
class m210831_102923_create_refresh_tokens_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%refresh_tokens}}', [
            'id' => $this->string(100),
            'access_token_id' => $this->string(100)->unique(),
            'revoked' => $this->boolean(),
            'expires_at' => $this->dateTime()->null(),
        ]);

        $this->addPrimaryKey(
            'id',
            'refresh_tokens',
            'id'
        );

        $this->addForeignKey(
            'fk-refresh_tokens_access_token_id-access_tokens_id',
            'refresh_tokens',
            'access_token_id',
            'access_tokens',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%refresh_tokens}}');
    }
}
