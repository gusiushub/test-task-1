<?php

namespace app\service\author\dto;

class CreateDto 
{
    public $title;

    public function __construct($title)
    {
        $this->title = $title;
    }
}