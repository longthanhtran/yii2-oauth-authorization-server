<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%access_tokens}}`.
 */
class m210830_084542_create_access_tokens_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%access_tokens}}', [
            'id' => $this->string(100)->unique(),
            'user_id' => $this->bigInteger()->unsigned()->null(),
            'client_id' => $this->bigInteger()->unsigned(),
            'name' => $this->string()->null(),
            'scopes' => $this->text()->null(),
            'revoked' => $this->boolean(),
            'expires_at' => $this->datetime()->null(),
            'created_at' => $this->timestamp()->append(
                'DEFAULT CURRENT_TIMESTAMP'
            ),
            'updated_at' => $this->timestamp()->append(
                'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            )
        ]);

        $this->addForeignKey(
            'fk-access_tokens_client_id-clients_id',
            'access_tokens',
            'client_id',
            'clients',
            'id'
        );

        $this->addForeignKey(
            'fk-access_tokens_user_id-users_id',
            'access_tokens',
            'user_id',
            'users',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%access_tokens}}');
    }
}
