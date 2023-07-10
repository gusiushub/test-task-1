<?php

namespace app\models\db;

use yii\db\ActiveRecord;

class RolePermission extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'role_permission';
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
