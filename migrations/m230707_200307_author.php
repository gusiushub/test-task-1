<?php

use yii\db\Migration;

/**
 * Class m230707_200307_author
 */
class m230707_200307_author extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('author', [
            'author_id' => $this->primaryKey(),
            'title' => $this->string(256)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP')
        ]);
        
        $this->addForeignKey(
            'fk-book-author_id',
            'book',
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
        $this->dropTable('author');
    }

}
