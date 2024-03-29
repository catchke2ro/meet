<?php


use app\components\AuthManager;
use app\models\User;

$params = require __DIR__ . '/params.php';
if (file_exists(__DIR__ . '/params.local.php')) {
	$params = array_replace_recursive($params, require __DIR__ . '/params.local.php');
}

$db = require __DIR__ . '/db.php';
if (file_exists(__DIR__ . '/db.local.php')) {
	$db = array_replace_recursive($db, require __DIR__ . '/db.local.php');
}

$config = [
	'language'   => 'hu-HU',
	'id'         => 'basic',
	'basePath'   => dirname(__DIR__),
	'bootstrap'  => ['log', 'admin'],
	'aliases'    => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'components' => [
		'authManager'  => [
			'class' => AuthManager::class,
		],
		'request'      => [
			'cookieValidationKey' => 'OMLFrqqEvaSHG2qTgZTAv4W8uIp-FoDa',
			'trustedHosts'        => [
				'10.0.3.1'
			],
		],
		'session'      => [
			'timeout'      => 86400,
			'class'        => 'yii\web\Session',
			'cookieParams' => ['lifetime' => 86400]
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
				'host'       => 'smtp.lutheran.hu',
				'port'       => '587',
				'username'   => 'meet',
				'password'   => '5qUvw2QSAcm',
			],
		],
		'i18n'         => [
			'translations' => [
				'meet' => [
					'class'          => 'yii\i18n\PhpMessageSource',
					'basePath'       => '@app/messages', // if advanced application, set @frontend/messages
					'sourceLanguage' => 'hu',
				],
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
		'urlManager'   => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
			'rules'           => [
				'/'                        => 'site/home',
				//'/resztvevok'              => 'site/participants',
				'/aktivitas'               => 'site/posts',
				'/programleiras'           => 'site/description',
				'/aef'                     => 'site/aef',
				'/modulok'                 => 'site/modules',
				'/dokumentumok'            => 'site/documents',
				'/adatkezelesi-szabalyzat' => 'site/terms',
				'/impresszum'              => 'site/impressum',
				'/kerdesek'                => 'question/index',
				'/vallalasok'              => 'commitment/index',
				'/regisztracio'            => 'user/registration',
				'/belepes'                 => 'user/login',
				'/elfelejtett-jelszo'      => 'user/forgot-password',
				'/jelszo-visszaallitas'    => 'user/reset-password',
				'/kilepes'                 => 'user/logout',
				'/vallalasok/pontok'       => 'commitment/score',
				'/vallalasok/history'      => 'commitment/history',
				'/vallalasok/vege'         => 'commitment/end',
				'/uzenet'                  => 'site/org-contact',
				'/en'                      => 'site/description-en',

				'/_org-list'           => 'ajax/org-list',
				'/_authorization-file' => 'user/get-authorization-file',
				'/_orgs'               => 'ajax/organizations',
				'/_ci-download'        => 'site/ci-download'
			],
		],
	],
	'params'     => $params,
	'modules'    => [
		'admin' => [
			'class'     => 'app\modules\admin\Module',
			'layout'    => 'admin',
			'as access' => [
				'class' => \yii\filters\AccessControl::class,
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin'],
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
