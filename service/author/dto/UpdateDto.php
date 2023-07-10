<?php

namespace app\service\author\dto;

class UpdateDto 
{
    public $title;
    public $authorId;

    public function __construct($title, $authorId)
    {
        $this->title = $title;
        $this->authorId = $authorId;
    }
}