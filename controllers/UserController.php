<?php

namespace app\controllers;

use app\components\Email;
use app\models\forms\ForgotPassword;
use app\models\forms\Login;
use app\models\forms\Registration;
use app\models\forms\ResetPassword;
use app\models\Organization;
use app\models\Person;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
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
	public function behaviors(): array {
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => ['login', 'registration', 'get-authorization-file', 'forgot-password', 'reset-password'],
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
				'class'   => VerbFilter::class,
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}


	/**
	 * {@inheritdoc}
	 */
	public function actions(): array {
		return [];
	}


	/**
	 * @return Response|string
	 * @throws InvalidConfigException
	 * @throws \yii\db\Exception
	 */
	public function actionRegistration(): Response|string {
		$model = new Registration();

		if ($model->load(Yii::$app->request->post())) {
			if (($return = $model->signup())) {
				[$personId, $orgId, $attachment] = $return;
				$person = Person::findOne(['id' => $personId]);
				$organization = Organization::findOne(['id' => $orgId]);
				$email = $person->getEmail();

				(new Email())->sendEmail('new_registration', $email->email, 'MEET Értesítő regisztráció kezdeményezésről', [
					'person' => $person
				]);

				(new Email())->sendEmail('new_registration_admin', 'meet@lutheran.hu', 'Új regisztráció', [
					'person'       => $person,
					'organization' => $organization
					], [
					$attachment
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
	 * @return Response|string
	 */
	public function actionLogin(): Response|string {
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
	 * @return Response|string
	 * @throws Exception
	 */
	public function actionForgotPassword(): Response|string {
		$model = new ForgotPassword();

		if ($model->load(Yii::$app->request->post())) {
			if (($email = $model->submit())) {
				$user = User::findOne(['email' => $email]);

				$user->passwordResetToken = uniqid(Yii::$app->security->generateRandomString(), true);
				$user->passwordResetExpiresAt = date('Y-m-d H:i:s', time() + 3600);
				$user->save();

				(new Email())->sendEmail('forgot_password', $email, 'MEET Elfelejtett jelszó', [
					'user' => $user
				]);
			}
			Yii::$app->session->setFlash('success', 'Amennyiben az e-mail cím létezik, akkor elküldtük a jelszó visszaállításához szükséges információkat.');

			return $this->redirect(Url::toRoute('user/login'));
		}

		Yii::$app->view->params['pageClass'] = 'forgotPassword';

		return $this->render('forgot-password', [
			'model' => $model,
		]);
	}


	/**
	 * @return Response|string
	 * @throws HttpException
	 */
	public function actionResetPassword(): Response|string {
		$model = new ResetPassword();

		if ($model->load(Yii::$app->request->post())) {
			if (($passwordHash = $model->reset()) && ($user = User::findOne(['password_reset_token' => $model->token]))) {
				$user->passwordResetToken = null;
				$user->passwordResetExpiresAt = null;
				$user->password = $passwordHash;
				$user->save();
				Yii::$app->session->setFlash('success', 'Sikeresen megváltoztattad a jelszavad. Most már bejelentkezhetsz.');

				return $this->redirect(Url::toRoute('user/login'));
			}
			Yii::$app->session->setFlash('error', 'Hiba történt a jelszó visszaállításakor. Kérlek próbáld újra.');
		}

		$token = $model->token ?: $this->request->get('t');
		if (!$token) {
			throw new HttpException(404);
		}
		$user = User::findOne(['password_reset_token' => $token]);
		if (!$user || $user->passwordResetExpiresAt < time()) {
			Yii::$app->session->setFlash('error', 'A jelszó visszaállítási link lejárt vagy érvénytelen.');

			return $this->redirect(Url::toRoute('user/login'));
		}

		Yii::$app->view->params['pageClass'] = 'resetPAssword';

		return $this->render('reset-password', [
			'model' => $model,
			'token' => $token,
		]);
	}


	/**
	 * @return Response
	 */
	public function actionLogout(): Response {
		Yii::$app->user->logout();

		return $this->goHome();
	}


}
