<?php

namespace app\models\response;

use app\models\db\Book as ModelsBook;
use yii\base\Model;

class Book extends Model
{
    public $bookId;
    public $title;
    public $description;
    public $releaseYear;
    public $author;
    public $isbn;
    public $coverArt;
    public $createdAt;
    public $updatedAt;

    public static function fromModel(ModelsBook $book)
    {
        return new static([
            'bookId' => $book->book_id,
            'title' => $book->title,
            'description' => $book->description,
            'releaseYear' => $book->release_year,
            'author' => array_map(function($author) {
                return Author::fromModel($author);
            }, $book->authors),
            'isbn' => $book->isbn,
            'coverArt' => $book->cover_art,
            'createdAt' => $book->created_at,
            'updatedAt' => $book->updated_at,
        ]);
    }
}