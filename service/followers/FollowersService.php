<?php

namespace app\service\followers;

use DateTime;
use yii\helpers\Json;
use app\models\db\Followers;
use yii\web\BadRequestHttpException;

class FollowersService implements \app\service\followers\FollowersServiceInterface
{
    /**
     * @param integer $msisdn
     * @param integer $authorId
     * @return Followers
     */
    public function create(int $msisdn, int $authorId): Followers
    {
        $followers = new Followers();
        $followers->setScenario(Followers::SCENARIO_CREATE);
        $followers->setAttributes([
            'msisdn' => $msisdn,
            'author_id' => $authorId,
            'created_at' => (new DateTime())->format('Y-m-d H:i:s')
        ]);
        
        if (!$followers->validate()) {
            throw new BadRequestHttpException(Json::encode($followers->errors));
        }

        $followers->save();

        return $followers;
    }
}