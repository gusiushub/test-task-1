<?php

namespace app\service\book\dto;

class CreateDto 
{
    public $title;
    public $description;
    public $releaseYear;
    public $isbn;
    public $coverArt;
    public $authorId;

    public function __construct($title, $description, $releaseYear, $isbn, $coverArt, $authorId)
    {
        $this->title = $title;
        $this->description = $description;
        $this->releaseYear = $releaseYear;
        $this->isbn = $isbn;
        $this->coverArt = $coverArt;
        $this->authorId = $authorId;
    }
}