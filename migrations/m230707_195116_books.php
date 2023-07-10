<?php

use yii\db\Migration;

/**
 * Class m230707_195116_books
 */
class m230707_195116_books extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('book', [
            'book_id' => $this->primaryKey(),
            'title' => $this->string(256)->notNull(),
            'description' => $this->text(),
            'release_year' => $this->text()->notNull(),
            'isbn' => $this->text(),
            'cover_art' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'role_id' => $this->integer(),
            'author_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('book');
    }
}
