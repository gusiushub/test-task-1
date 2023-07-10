<?php

namespace app\models\response;

use app\models\db\Followers as DbFollowers;
use yii\base\Model;

class Followers extends Model
{
    public $followerId;
    public $msisdn;
    public $createdAt;

    public static function fromModel(DbFollowers $followers)
    {
        return new static([
            'followerId' => $followers->follower_id,
            'msisdn' => $followers->msisdn,
            'createdAt' => $followers->created_at,
        ]);
    }
}