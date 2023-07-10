<?php

use app\models\db\Permission;
use yii\db\Migration;

/**
 * Class m230710_104606_permission
 */
class m230710_104606_permission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('permission', [
            'permission_id' => $this->primaryKey(),
            'title' => $this->text(),
            'permission' => $this->string(256)->notNull(),
        ]);

        $this->insert('permission', [
            'permission' => Permission::CREATE_BOOK,
        ]);
        $this->insert('permission', [
            'permission' => Permission::DELETE_BOOK,
        ]);
        $this->insert('permission', [
            'permission' => Permission::VIEW_BOOK,
        ]);
        $this->insert('permission', [
            'permission' => Permission::EDIT_BOOK,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('permission');
    }
}
