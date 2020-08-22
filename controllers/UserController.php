<?php

namespace app\controllers;

use app\models\forms\Login;
use app\models\forms\Registration;
use app\models\lutheran\Person;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class UserController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class UserController extends Controller {


	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['login', 'registration'],
						'allow' => true,
					],
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}


	/**
	 * {@inheritdoc}
	 */
	public function actions() {
		return [];
	}


	/**
	 * @return string|Response
	 */
	public function actionRegistration() {
		$model = new Registration();

		if ($model->load(Yii::$app->request->post())) {
			if (($personId = $model->signup())) {
				$person = Person::findOne(['id' => $personId]);
				$email = $person->getEmail();

				Yii::$app->mailer->compose('new_registration', [
					'person' => $person
				])
					->setTo($email)
					->setSubject('MEET - Új regisztráció')
					->send();

				return $this->redirect(Url::to('user/login'));
			}
		}

		Yii::$app->view->params['pageClass'] = 'registration';
		return $this->render('registration', [
			'model' => $model,
		]);
	}


	/**
	 * @return string|Response
	 */
	public function actionLogin() {
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new Login();

		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		}

		Yii::$app->view->params['pageClass'] = 'login';
		return $this->render('login', [
			'model' => $model,
		]);
	}


	/**
	 * @return string|Response
	 */
	public function actionLogout() {
		Yii::$app->user->logout();

		return $this->goHome();
	}


}
