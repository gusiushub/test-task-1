<?php

namespace app\service\book\dto;


class UpdateDto 
{
    public $bookId;
    public $title;
    public $description;
    public $releaseYear;
    public $isbn;
    public $coverArt;

    public function __construct($bookId, $title, $description, $releaseYear, $isbn, $coverArt)
    {
        $this->bookId = $bookId;
        $this->title = $title;
        $this->description = $description;
        $this->releaseYear = $releaseYear;
        $this->isbn = $isbn;
        $this->coverArt = $coverArt;
    }}