<?php

namespace app\controllers;

use app\models\db\Followers as DbFollowers;
use app\models\response\Followers;
use app\service\followers\FollowersServiceInterface;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class FollowersController extends ApiController
{
    private $followersService;

    public function __construct($id, $module, FollowersServiceInterface $followersService, $config)
    {
        $this->followersService = $followersService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    protected function verbs(): array
    {
        return [
            'create' => ['POST'],
            'list' => ['GET'],
        ];
    }

    /**
     * @return ResponseBook
     */
    public function actionCreate(int $msisdn, $authorId): Followers
    {
        $follower = $this->followersService->create($msisdn, $authorId);
        return Followers::fromModel($follower);
    }

    /**
     * @return array
     */
    public function actionList(): array
    {
        $query = DbFollowers::find();        
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy('follower_id DESC'),
            'totalCount' => (int)$query->count(),
        ]);
        $dataProvider->prepare();

        return [
            'page' => $dataProvider->pagination->page + 1,
            'perPage' => $dataProvider->pagination->pageSize,
            'totalCount' => $dataProvider->getTotalCount(),
            'lastPage' => $dataProvider->pagination->pageCount - 1 <= $dataProvider->pagination->page,
            'followers' => array_map(function($follower) {
                return Followers::fromModel($follower);
            }, $dataProvider->getModels())
        ];
    }

}