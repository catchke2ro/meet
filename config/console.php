<?php

$params = require __DIR__ . '/params.php';
if (file_exists(__DIR__.'/params.local.php')) {
	$params = array_replace_recursive($params, require __DIR__ . '/params.local.php');
}

$db = require __DIR__ . '/db.php';
if (file_exists(__DIR__.'/db.local.php')) {
	$db = array_replace_recursive($db, require __DIR__ . '/db.local.php');
}

$dbTk = require __DIR__ . '/dbtk.php';
if (file_exists(__DIR__.'/dbtk.local.php')) {
	$dbTk = array_replace_recursive($dbTk, require __DIR__ . '/dbtk.local.php');
}

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
				//'host'       => 'smtp.lutheran.hu',
				'host'       => 'in.mailjet.com',
				'port'       => '587',
				//'username'   => 'meet',
				'username'   => 'd06cb8e360ee26230b0112ac63b270ba',
				//'password'   => '5qUvw2QSAcm',
				'password'   => '3f1cfc2311249f5b07efe2d6889d6512',
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
