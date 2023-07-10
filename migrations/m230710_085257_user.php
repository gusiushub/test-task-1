<?php

use yii\db\Migration;

/**
 * Class m230710_085257_user
 */
class m230710_085257_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'user_id' => $this->primaryKey(),
            'username' => $this->string(256)->notNull(),
            'password' => $this->text()->notNull(),
            'auth_key' => $this->text(),
            'access_token' => $this->text(),
            'role_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
