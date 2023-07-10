<?php

use yii\db\Migration;

/**
 * Class m230710_225650_followers
 */
class m230710_225650_followers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('followers', [
            'follower_id' => $this->primaryKey(),
            'msisdn' => $this->bigInteger(),
            'author_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-followers-author_id',
            'followers',
            'author_id',
            'author',
            'author_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('followers');
    }
}
