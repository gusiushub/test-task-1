<?php

namespace app\models\response;

use yii\base\Model;

class BaseResponse extends Model
{
    public $result = 0;

    public $requestId;

    protected $_httpCode = 200;

    public function init()
    {
        $this->requestId = \Yii::$app->security->generateRandomString(8) . '.' . time();
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->_httpCode;
    }

    public function __construct(array $config = [], int $httpCode = 200)
    {
        $this->_httpCode = $httpCode;
        parent::__construct($config);
    }
}