<?php

namespace app\models\db;

use yii\db\ActiveRecord;

class Role extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'role';
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
