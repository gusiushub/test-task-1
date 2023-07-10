<?php

namespace app\models\db;

use yii\db\ActiveRecord;

class Permission extends ActiveRecord
{
    const DELETE_BOOK = 'delete_book';

    const VIEW_BOOK = 'view_book';

    const CREATE_BOOK = 'create_book';

    const EDIT_BOOK = 'edit_book';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'permission';
    }


}