<?php

use yii\db\Migration;

/**
 * Class m230710_104621_role_permission
 */
class m230710_104621_role_permission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('role_permission', [
            'role_id' => $this->integer(),
            'permission_id' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-role_permission-role_id',
            'role_permission',
            'role_id'
        );

        $this->addForeignKey(
            'fk-role_permission-role_id',
            'role_permission',
            'role_id',
            'role',
            'role_id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-role_permission-permission_id',
            'role_permission',
            'permission_id'
        );

        $this->addForeignKey(
            'fk-role_permission-permission_id',
            'role_permission',
            'permission_id',
            'permission',
            'permission_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('role_permission');
    }
}
