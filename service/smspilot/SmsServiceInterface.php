<?php 

namespace app\service\smspilot;

interface SmsServiceInterface
{
    public function sendSms();
    public function setMsg(string $msg);
    public function setMsisdn(string $msisdn);
}