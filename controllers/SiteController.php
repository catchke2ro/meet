<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * Class SiteController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class SiteController extends Controller {


	/**
	 * {@inheritdoc}
	 */
	public function actions() {
		return [
			'error'   => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class'           => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}


	/**
	 * Displays Home
	 *
	 * @return string
	 */
	public function actionHome() {
		return $this->render('home');
	}


}
