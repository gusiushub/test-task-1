<?php

namespace app\models\db;

use yii\db\ActiveRecord;

class Book extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @return array
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_CREATE => ['title', 'description', 'release_year', 'isbn', 'cover_art', 'created_at', 'author_id'],
            self::SCENARIO_UPDATE => ['title', 'description', 'release_year', 'isbn', 'cover_art', 'updated_at'],
        ];
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'book';
    }
    
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['title', 'description', 'release_year', 'isbn', 'cover_art', 'author_id'], 'required', 'on' => self::SCENARIO_CREATE],
            ['isbn', 'unique'],
            [['title', 'description'], 'string'],
            [['release_year', 'created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['author_id'], 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'author_id', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
        ];
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['author_id' => 'author_id']);
    }


}
