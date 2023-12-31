<?php

use app\service\author\AuthorService;
use app\service\author\AuthorServiceInterface;
use app\service\book\BookService;
use app\service\book\BookServiceInterface;
use app\service\followers\FollowersService;
use app\service\followers\FollowersServiceInterface;
use app\service\smspilot\SmsService;
use app\service\smspilot\SmsServiceInterface;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'books',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'container' => [
        'definitions' => [
            SmsServiceInterface::class => SmsService::class,
            BookServiceInterface::class => BookService::class,
            AuthorServiceInterface::class => AuthorService::class,
            FollowersServiceInterface::class => FollowersService::class
        ]
    ],
    'aliases' => [
    ],

    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => \yii\web\JsonParser::class,
            ],
        ],
        'response' => [
            'class' => \yii\web\Response::class,
            'format' => 'json',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\db\User',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => \app\models\AuthManager::class,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'book/<id:\d+>/delete' => 'book/delete',
                'book/<id:\d+>/update' => 'book/update',
                'book/<id:\d+>' => 'book/get',
                'book/list' => 'book/list',

                'author/<id:\d+>' => 'author/get',
                'author/<id:\d+>/delete' => 'author/delete',
                'author/<id:\d+>/update' => 'author/update',
                'author/list' => 'author/list',

                'followers/<msisdn:\d+>/author/<authorId:\d+>' => 'followers/create',
                'followers/list' => 'follower/list',
            ],
        ],
    ],
    'params' => $params,
];

return $config;
