<?php

namespace app\controllers;

use app\components\Email;
use app\models\forms\Login;
use app\models\forms\Registration;
use app\models\lutheran\Person;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
						'actions' => ['login', 'registration', 'get-authorization-file'],
						'allow'   => true,
					],
					[
						'actions' => ['logout'],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
			'verbs'  => [
				'class'   => VerbFilter::className(),
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

				(new Email())->sendEmail('new_registration', $email, 'MEET Értesítő regisztráció kezdeményezésről', [
					'person' => $person
				]);

				Yii::$app->session->setFlash('success', 'Sikeresen kezdeményzted a regisztrációdat. E-mail-ben értesítünk, amint aktiváltuk a regisztrációt.');

				return $this->redirect('/');
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
			return $this->redirect('/');
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


	/**
	 * @param $id
	 * @param $token
	 *
	 * @return \yii\console\Response|Response
	 * @throws NotFoundHttpException
	 */
	public function actionGetAuthorizationFile($id, $token) {
		$validToken = Yii::$app->params['token'];
		$baseDir = Yii::$app->getBasePath() . '/storage/authorizations';
		if (!(!empty($id) && $token && $validToken === $token && file_exists($baseDir . '/' . $id . '.pdf'))) {
			throw new NotFoundHttpException();
		}

		return Yii::$app->response->sendFile($baseDir . '/' . $id . '.pdf');
	}


}
