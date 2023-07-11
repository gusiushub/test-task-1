<?php

namespace app\controllers;

use app\models\db\Author;

class ReportController extends ApiController
{
    public function actionTopTen()
    {
        $report = Author::find()->select('author.author_id, author.title, count(*) as cnt')
                                ->joinWith('books')
                                ->groupBy('author.author_id, author.title')
                                ->orderBy('cnt DESC')
                                ->limit(10)
                                ->all();

        return array_map(function($value){
            return [
                'author_id' => $value->author_id,
                'title' => $value->title,
                'cnt' => count($value->books)
           ]; 
        }, $report);
    }
}