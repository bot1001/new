<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'defaultRoute' => '/site/index',
	'language' => 'zh-CN',
	'timeZone' => 'Asia/Shanghai',
    'controllerNamespace' => 'frontend\controllers',
	'modules' => [
	       'gridview' =>  [
                'class' => '\kartik\grid\Module'
            ],
	],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager'=>[
	        'enablePrettyUrl' => true,
	        'showScriptName' => false,
	        'rules' => [
		        '<controller:(post|comment)>/<id:\d+>/<action:(create|update|delete)>' =>'<controller>/<action>',
	            '<controller:(post|comment)>/<id:\d+>' => '<controller>/read',
	            '<controller:(post|comment)>s' => '<controller>/list',
	         ]
	    ],
        
    ],
    'params' => $params,
];
