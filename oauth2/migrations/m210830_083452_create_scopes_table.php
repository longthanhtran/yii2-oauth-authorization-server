<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%scope}}`.
 */
class m210830_083452_create_scopes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%scopes}}', [
            'id' => $this->primaryKey(),
            'identifier' => $this->string(255),
            'name' => $this->string(255),
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
        $this->dropTable('{{%scope}}');
    }
}
