<?php

use yii\db\Migration;

/**
 * Class m230707_200451_book_author
 */
class m230707_200451_book_author extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('book_author', [
            'author_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
        ]);
        
        $this->createIndex(
            'idx-book_author-author_id',
            'book_author',
            'author_id'
        );

        $this->addForeignKey(
            'fk-book_author-author_id',
            'book_author',
            'author_id',
            'author',
            'author_id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-book_author-book_id',
            'book_author',
            'book_id'
        );

        $this->addForeignKey(
            'fk-book_author-book_id',
            'book_author',
            'book_id',
            'book',
            'book_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('book_author');
    }
}
