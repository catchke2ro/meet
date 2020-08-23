<?php

namespace app\controllers;

use Yii;
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

		Yii::$app->view->params['pageClass'] = 'home';
		return $this->render('home');
	}


	/**
	 * Displays Home
	 *
	 * @return string
	 */
	public function actionTerms() {

		Yii::$app->view->params['pageClass'] = 'terms';
		return $this->render('terms');
	}


	/**
	 * Displays Home
	 *
	 * @return string
	 */
	public function actionDocuments() {

		Yii::$app->view->params['pageClass'] = 'documents';
		return $this->render('documents');
	}


	/**
	 * Displays Home
	 *
	 * @return string
	 */
	public function actionParticipants() {

		Yii::$app->view->params['pageClass'] = 'participants';
		return $this->render('participants');
	}


}
