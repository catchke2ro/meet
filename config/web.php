<?php


use app\models\lutheran\User;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$dbmail = require __DIR__ . '/dbmail.php';

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
			'useFileTransport' => false,
			'transport'        => [
				'class'      => 'Swift_SmtpTransport',
				'encryption' => 'tls',
				'host'       => 'in-v3.mailjet.com',
				'port'       => '587',
				'username'   => 'd06cb8e360ee26230b0112ac63b270ba',
				'password'   => '3f1cfc2311249f5b07efe2d6889d6512',
			],
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
		'dbmail'       => $dbmail,
		'urlManager'   => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
			'rules'           => [
				'/'                        => 'site/home',
				//'/resztvevok'              => 'site/participants',
				'/aktivitas'               => 'site/posts',
				'/programleiras'           => 'site/description',
				'/modulok'                 => 'site/modules',
				'/dokumentumok'            => 'site/documents',
				'/adatkezelesi-szabalyzat' => 'site/terms',
				'/kerdesek'                => 'question/index',
				'/vallalasok'              => 'commitment/index',
				'/regisztracio'            => 'user/registration',
				'/belepes'                 => 'user/login',
				'/kilepes'                 => 'user/logout',
				'/vallalasok/pontok'       => 'commitment/score',
				'/vallalasok/history'      => 'commitment/history',
				'/vallalasok/vege'         => 'commitment/end',
				'/uzenet'                  => 'site/org-contact',

				'/_org-list'           => 'ajax/org-list',
				'/_authorization-file' => 'user/get-authorization-file',
				'/_orgs'               => 'ajax/orgs'
			],
		],
	],
	'params'     => $params,
	'modules' => [
		'meet'        => [
			'class' => 'app\modules\meet\Module',
			'layout' => 'admin',
			'as access'  => [
				'class' => \yii\filters\AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		],
	]
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
