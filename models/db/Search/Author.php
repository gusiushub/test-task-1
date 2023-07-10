<?php

namespace app\models\db\search;

use app\models\db\Author as DbAuthor;
use yii\data\ActiveDataProvider;

class Author extends DbAuthor
{
    /**
     * @return ActiveDataProvider
     */
    public function search(): ActiveDataProvider
    {
        $query = DbAuthor::find();
                        
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy('author_id DESC'),
            'totalCount' => (int)$query->count(),
        ]);

        return $dataProvider;
    }
}
