<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_codes}}`.
 */
class m210831_105222_create_auth_codes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auth_codes}}', [
            'id' => $this->string(100),
            'user_id' => $this->bigInteger()->unsigned()->unique(),
            'client_id' => $this->bigInteger()->unsigned(),
            'scopes' => $this->text()->null(),
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
        $this->dropTable('{{%auth_codes}}');
    }
}
