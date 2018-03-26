<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
	'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
	                'categories' => ['yii\base\Controller::runAction',
									 //'yii\base\View::renderFile',
									 'yii\base\InlineAction::runWithParams'],
	                'logVars' => ['_POST','_SESSION'],
                    'levels' => ['error', 'warning','trace'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
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
	'modules' => [
            'gridview' =>  [
                'class' => '\kartik\grid\Module'
            ]
        ],
    'params' => $params,
];
