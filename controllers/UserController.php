<?php

namespace app\controllers;

use app\models\db\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\HttpException;

class UserController extends ApiController
{
    /**
     * @return array
     */
    protected function verbs(): array
    {
        return [
            'create' => ['POST'],
        ];
    }
    
    /**
     * @return User
     */
    public function actionCreate(): User
    {
        $bodyParams = \Yii::$app->request->post();

        $model = new User();

        $model->setScenario(User::SCENARIO_CREATE);

        $model->setAttributes([
            'username' => ArrayHelper::getValue($bodyParams, 'username', null),
            'password' => ArrayHelper::getValue($bodyParams, 'password', null),
            'role_id' => ArrayHelper::getValue($bodyParams, 'role_id', null),
        ]);

        if (!$model->validate()) {
            throw new HttpException(400, Json::encode($model->errors));
        }

        $model->save(false) && $model->refresh();
        $model->refreshToken();

        return $model;
    }



}