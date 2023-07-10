<?php

use yii\db\Migration;

/**
 * Class m230710_104554_role
 */
class m230710_104554_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('role', [
            'role_id' => $this->primaryKey(),
            'title' => $this->text(),
            'role' => $this->string(256)->notNull(),
        ]);

        $this->addForeignKey(
            'fk-user-role_id',
            'user',
            'role_id',
            'role',
            'role_id',
            'CASCADE'
        );

        $this->insert('role', [
            // 'title' => 'Администратор',
            'role' => 'admin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user-role_id',
            'user'
        );
        $this->dropTable('role');
    }
}
