<?php

namespace app\service\smspilot;

use yii\helpers\Json;
use GuzzleHttp\Client;
use app\service\smspilot\SmsServiceInterface;

class SmsService implements SmsServiceInterface
{
    public $apiKey;
    private $msg;
    private $msisdn;
    public $host;
    private $client;

    public function __construct($apiKey, $host)
    {
        $this->host = $host;
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => "https://{$this->host}/",
            'timeout' => 15,
            'connect_timeout' => 3,
            'headers' => [
                'content-type' => 'application/json'
            ]
        ]);
    }

    public function setMsg(string $msg)
    {
        $this->msg = $msg;
        return $this;
    }

    public function setMsisdn(string $msisdn)
    {
        $this->msisdn = $msisdn;
        return $this;
    }

    public function sendSms()
    {
        $response = $this->client->get("api.php?apikey={$this->apiKey}&send={$this->msg}&to={$this->msisdn}&from=INFORM&format=json", [
            'content-type' => 'application/json'
        ]);
        return Json::decode($response->getBody()->getContents());
    }
}