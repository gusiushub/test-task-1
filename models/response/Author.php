<?php

namespace app\models\response;

use app\models\db\Author as DbAuthor;
use yii\base\Model;

class Author extends Model
{
    public $authorId;
    public $title;
    public $createdAt;
    public $updatedAt;

    public static function fromModel(DbAuthor $book)
    {
        return new static([
            'authorId' => $book->author_id,
            'title' => $book->title,
            'createdAt' => $book->created_at,
            'updatedAt' => $book->updated_at,
        ]);
    }
}