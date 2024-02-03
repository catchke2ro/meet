<?php

namespace app\controllers;

use app\components\Email;
use app\models\forms\OrgContact;
use app\models\Module;
use app\models\Organization;
use app\models\Post;
use app\models\User;
use Throwable;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class SiteController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class SiteController extends BaseController {


	/**
	 * {@inheritdoc}
	 */
	public function actions(): array {
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
	 * @return string
	 */
	public function actionHome(): string {
		Yii::$app->view->params['pageClass'] = 'home';

		return $this->render('home');
	}


	/**
	 * Displays Home
	 * @return string
	 */
	public function actionTerms(): string {

		Yii::$app->view->params['pageClass'] = 'terms';

		return $this->render('terms');
	}


	/**
	 * Displays Home
	 * @return string
	 */
	public function actionImpressum(): string {

		Yii::$app->view->params['pageClass'] = 'impressum';

		return $this->render('impressum');
	}


	/**
	 * Displays Home
	 *
	 * @return string
	 */
	public function actionAef(): string {

		Yii::$app->view->params['pageClass'] = 'aef';

		return $this->render('aef');
	}


	/**
	 * Displays Home
	 *
	 * @return string
	 * @throws Throwable
	 */
	public function actionDocuments(): string {

		/** @var User $user */
		$activeModule = null;
		if (($user = Yii::$app->user->getIdentity()) && ($org = $user->getOrganization())) {
			if (($module = $org->getLatestApprovedModule())) {
				$activeModule = $module;
			} else {
				$activeModule = Module::firstModule();
			}
		}

		Yii::$app->view->params['pageClass'] = 'documents';

		return $this->render('documents', [
			'user'         => $user,
			'activeModule' => $activeModule
		]);
	}


	/**
	 * @param $module
	 * @param $file
	 *
	 * @return Response
	 * @throws HttpException
	 * @throws Throwable
	 */
	public function actionCiDownload($module, $file): Response {
		if (!(($module = Module::findOne(['id' => $module])) &&
			($user = Yii::$app->user->getIdentity()) &&
			($org = $user->getOrganization()) &&
			($userModule = $org->getLatestApprovedModule()) &&
			($module->id === $userModule->id)
		)) {
			throw new HttpException(404);
		}

		$rootDir = Yii::$app->getBasePath() . '/storage/ci';

		if (!file_exists($rootDir . '/' . $file)) {
			throw new HttpException(404);
		}

		return Yii::$app->response->sendFile($rootDir . '/' . $file);
	}


	/**
	 * Displays Home
	 *
	 * @return string
	 */
	public function actionParticipants(): string {

		Yii::$app->view->params['pageClass'] = 'participants';

		return $this->render('participants');
	}


	/**
	 * Displays Home
	 * @return string
	 */
	public function actionDescription(): string {

		Yii::$app->view->params['pageClass'] = 'description';

		return $this->render('description');
	}


	/**
	 * Displays Home
	 * @return string
	 */
	public function actionDescriptionEn(): string {

		Yii::$app->view->params['pageClass'] = 'description';
		Yii::$app->language = 'en-EN';

		return $this->render('descriptionEn');
	}


	/**
	 * Displays Home
	 * @return string
	 */
	public function actionModules(): string {

		Yii::$app->view->params['pageClass'] = 'modules';

		return $this->render('modules');
	}


	/**
	 * @return string
	 */
	public function actionPosts(): string {
		$posts = Post::find()
			->orderBy('order ASC')
			->all();

		Yii::$app->view->params['pageClass'] = 'posts';

		return $this->render('posts', [
			'posts' => $posts
		]);
	}


	/**
	 * @param int|null $orgId
	 *
	 * @return Response|string
	 * @throws NotFoundHttpException
	 */
	public function actionOrgContact(?int $orgId): Response|string {
		$model = new OrgContact();

		if (!($organization = Organization::findOne(['id' => $orgId]))) {
			throw new NotFoundHttpException();
		}

		if ($model->load(Yii::$app->request->post())) {
			if (($contact = $model->contact())) {
				(new Email())->sendEmail('org_contact', $contact->email, 'Kapcsolatfelvétel MEET-en keresztül', [
					'contact' => $contact,
					'organization' => $organization
				]);

				Yii::$app->session->setFlash('success', 'Sikeres üzenetküldés!');

				return $this->redirect(Url::to('/'));
			} else {
				Yii::$app->session->setFlash('error', 'Hiba történt az üzenetküldés során!');
			}
		}

		$model->orgId = $orgId;

		Yii::$app->view->params['pageClass'] = 'orgContact';

		return $this->render('orgContact', [
			'model'        => $model,
			'organization' => $organization
		]);
	}


}
