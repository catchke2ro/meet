<?php


use app\models\User;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
	'language'   => 'hu-HU',
	'id'         => 'basic',
	'basePath'   => dirname(__DIR__),
	'bootstrap'  => ['log'],
	'aliases'    => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'components' => [
		'request'      => [
			'cookieValidationKey' => 'OMLFrqqEvaSHG2qTgZTAv4W8uIp-FoDa',
		],
		'cache'        => [
			'class' => 'yii\caching\FileCache',
		],
		'user'         => [
			'identityClass'   => User::class,
			'enableAutoLogin' => true,
			'loginUrl'        => ['user/login']
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'mailer'       => [
			'class'            => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => true,
		],
		'log'          => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db'           => $db,
		'urlManager'   => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
			'rules'           => [
				'/'                   => 'site/home',
				'/kerdesek'           => 'question/index',
				'/vallalasok'         => 'commitment/index',
				'/regisztracio'       => 'user/registration',
				'/belepes'            => 'user/login',
				'/kilepes'            => 'user/logout',
				'/vallalasok/pontok'  => 'commitment/score',
				'/vallalasok/history' => 'commitment/history',

				'/admin' => 'admin/index/index',

				'/admin/users'                 => 'admin/users/index',
				'/admin/users/create'          => 'admin/users/create',
				'/admin/users/edit/<id:\d+>'   => 'admin/users/edit',
				'/admin/users/delete/<id:\d+>' => 'admin/users/delete',

				'/admin/question-categories'                 => 'admin/question-categories/index',
				'/admin/question-categories/create'          => 'admin/question-categories/create',
				'/admin/question-categories/edit/<id:\d+>'   => 'admin/question-categories/edit',
				'/admin/question-categories/delete/<id:\d+>' => 'admin/question-categories/delete',

				'/admin/question-items/<categoryId:\d+>'        => 'admin/question-items/index',
				'/admin/question-items/create/<categoryId:\d+>' => 'admin/question-items/create',
				'/admin/question-items/edit/<id:\d+>'           => 'admin/question-items/edit',
				'/admin/question-items/delete/<id:\d+>'         => 'admin/question-items/delete',

				'/admin/question-options/<itemId:\d+>'        => 'admin/question-options/index',
				'/admin/question-options/create/<itemId:\d+>' => 'admin/question-options/create',
				'/admin/question-options/edit/<id:\d+>'       => 'admin/question-options/edit',
				'/admin/question-options/delete/<id:\d+>'     => 'admin/question-options/delete',

				'/admin/commitment-categories'                 => 'admin/commitment-categories/index',
				'/admin/commitment-categories/create'          => 'admin/commitment-categories/create',
				'/admin/commitment-categories/edit/<id:\d+>'   => 'admin/commitment-categories/edit',
				'/admin/commitment-categories/delete/<id:\d+>' => 'admin/commitment-categories/delete',

				'/admin/commitment-items/<categoryId:\d+>'        => 'admin/commitment-items/index',
				'/admin/commitment-items/create/<categoryId:\d+>' => 'admin/commitment-items/create',
				'/admin/commitment-items/edit/<id:\d+>'           => 'admin/commitment-items/edit',
				'/admin/commitment-items/delete/<id:\d+>'         => 'admin/commitment-items/delete',

				'/admin/commitment-options/<itemId:\d+>'        => 'admin/commitment-options/index',
				'/admin/commitment-options/create/<itemId:\d+>' => 'admin/commitment-options/create',
				'/admin/commitment-options/edit/<id:\d+>'       => 'admin/commitment-options/edit',
				'/admin/commitment-options/delete/<id:\d+>'     => 'admin/commitment-options/delete',

				'/admin/modules'                 => 'admin/modules/index',
				'/admin/modules/create'          => 'admin/modules/create',
				'/admin/modules/edit/<id:\d+>'   => 'admin/modules/edit',
				'/admin/modules/delete/<id:\d+>' => 'admin/modules/delete',

			],
		],
	],
	'params'     => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];
}

return $config;
