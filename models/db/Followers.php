<?php

namespace app\models\db;

use yii\db\ActiveRecord;

class Followers extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    /**
     * @return array
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_CREATE => ['msisdn', 'created_at', 'author_id'],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['msisdn'], 'required', 'on' => self::SCENARIO_CREATE],
            [['msisdn'], 'match', 'pattern' => "/^7\d{10}$/", 'on' => self::SCENARIO_CREATE],
            [['created_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['author_id'], 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'author_id', 'on' => [self::SCENARIO_CREATE]],
        ];
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'followers';
    }
}