<?php

namespace app\controllers;

use app\behaviors\ApiRequestValidatorModelInterface;
use app\models\response\BaseResponse;
use Psr\Log\LogLevel;
use yii\base\ErrorException;
use yii\base\Model;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Базовый контроллер API
 *
 * @package app\components
 */
class ApiController extends Controller
{

    /**
     * @var ApiRequestValidatorModelInterface
     */
    protected $_request;

    /**
     * @param Model $request
     */
    public function setRequest(Model $request)
    {
        $this->_request = $request;
    }

    /**
     * @return Model
     * @throws ErrorException
     */
    public function getRequest(): Model
    {
        if (!$this->_request) {
            throw new ErrorException('Request is not been set');
        }
        return $this->_request;
    }


    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
        ];
    }

    /**
     * @param \yii\base\Action $action
     *
     * @return bool
     */
    public function beforeAction($action): bool
    {
        $request = \Yii::$app->request;
        $params = $request->getBodyParams();

        \Yii::getLogger()->log('Request: ' . Json::encode([
                'url' => $request->getAbsoluteUrl(),
                'params' => $request->get(),
                'bodyParams' => $params,
                'client' => [
                    'agent' => $request->getUserAgent(),
                    'ip' => $request->getUserIP(),
                ],
            ]), LogLevel::INFO, 'request');

        return parent::beforeAction($action);
    }


    public function afterAction($action, $result)
    {
        if ($result instanceof BaseResponse) {
            \Yii::$app->response->statusCode = $result->getHttpCode();
        }

        $request = \Yii::$app->request;
        $params = $request->getBodyParams();

        $user = \Yii::$app->user ? \Yii::$app->user->identity : (object) ['login' => 'guest'];
        unset($user->password, $user->access_token, $user->created_at);

        \Yii::getLogger()->log('Request: ' . Json::encode([
                'user' => $user,
                'url' => $request->getAbsoluteUrl(),
                'params' => $request->get(),
                'bodyParams' => $params,
                //'result' => $result,
                'client' => [
                    'agent' => $request->getUserAgent(),
                    'ip' => $request->getUserIP(),
                ],
            ]), LogLevel::INFO, 'user_actions');

        return parent::afterAction($action, $result);
    }

}
