<?php

namespace app\models\db;

use yii\db\ActiveRecord;

class Author extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @return array
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_CREATE => ['title', 'created_at'],
            self::SCENARIO_UPDATE => ['title', 'updated_at'],
        ];
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'author';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['title'], 'required', 'on' => self::SCENARIO_CREATE],
            [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d H:i:s']
        ];
    }

    public function getBooks()
    {
        return $this->hasMany(Book::class, ['author_id' => 'author_id']);
    }

}
