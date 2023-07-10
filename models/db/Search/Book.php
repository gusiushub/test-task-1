<?php

namespace app\models\db\search;

use yii\data\ActiveDataProvider;
use app\models\db\Book as DbBook;

class Book extends DbBook
{
    /**
     * @return ActiveDataProvider
     */
    public function search(): ActiveDataProvider
    {
        $query = DbBook::find();
                        
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy('book_id DESC'),
            'totalCount' => (int)$query->count(),
        ]);

        return $dataProvider;
    }
}
