<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
	'id'                  => 'basic-console',
	'basePath'            => dirname(__DIR__),
	'bootstrap'           => ['log'],
	'controllerNamespace' => 'app\commands',
	'aliases'             => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
		'@tests' => '@app/tests',
	],
	'components'          => [
		'cache'       => [
			'class' => 'yii\caching\FileCache',
		],
		'log'         => [
			'targets' => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db'          => $db,
		'mailer'       => [
			'class'            => 'yii\swiftmailer\Mailer',
			'useFileTransport' => false,
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'encryption' => 'tls',
				'host' => 'in-v3.mailjet.com',
				'port' => '587',
				'username' => 'd06cb8e360ee26230b0112ac63b270ba',
				'password' => '3f1cfc2311249f5b07efe2d6889d6512',
			],
		],
	],
	'params'              => $params,
	/*
	'controllerMap' => [
		'fixture' => [ // Fixture generation command line.
			'class' => 'yii\faker\FixtureController',
		],
	],
	*/
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
	];
}

return $config;
