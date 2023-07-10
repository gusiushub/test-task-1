<?php

namespace app\models\db;

use yii\db\ActiveRecord;

class BookAuthor extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'book_author';
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_CREATE => ['book_id', 'author_id',],
        ];
    }


    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            
        ];
    }

}
