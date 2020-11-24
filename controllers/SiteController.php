<?php

namespace app\controllers;

use app\models\forms\OrgContact;
use app\models\lutheran\Organization;
use app\models\Module;
use app\models\Post;
use meetbase\models\lutheran\User;
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
	public function actionImpressum() {

		Yii::$app->view->params['pageClass'] = 'impressum';

		return $this->render('impressum');
	}


	/**
	 * Displays Home
	 *
	 * @return string
	 */
	public function actionAef() {

		Yii::$app->view->params['pageClass'] = 'aef';

		return $this->render('aef');
	}


	/**
	 * Displays Home
	 *
	 * @return string
	 * @throws Throwable
	 */
	public function actionDocuments() {

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
			'user' => $user,
			'activeModule' => $activeModule
		]);
	}


	/**
	 * @param $id
	 * @param $token
	 *
	 * @return \yii\console\Response|Response
	 * @throws NotFoundHttpException
	 * @throws HttpException
	 */
	public function actionCiDownload($module, $file) {
		if (!(($module = Module::findOne(['id' => $module])) &&
			($user = Yii::$app->user->getIdentity()) &&
			($org = $user->getOrganization()) &&
			($userModule = $org->getLatestApprovedModule()) &&
			($module->id === $userModule->id)
		)) {
			throw new HttpException(404);
		}

		$rootDir = Yii::$app->getBasePath().'/storage/ci';

		if (!file_exists($rootDir.'/'.$file)) {
			throw new HttpException(404);
		}

		return Yii::$app->response->sendFile($rootDir.'/'.$file);
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


	/**
	 * Displays Home
	 *
	 * @return string
	 */
	public function actionDescription() {

		Yii::$app->view->params['pageClass'] = 'description';

		return $this->render('description');
	}


	/**
	 * Displays Home
	 *
	 * @return string
	 */
	public function actionModules() {

		Yii::$app->view->params['pageClass'] = 'modules';

		return $this->render('modules');
	}


	/**
	 * @return string
	 */
	public function actionPosts() {
		$posts = Post::find()
			->orderBy('order ASC')
			->all();

		Yii::$app->view->params['pageClass'] = 'posts';
		return $this->render('posts', [
			'posts' => $posts
		]);
	}


	/**
	 * @param $orgId
	 *
	 * @return string|Response
	 * @throws NotFoundHttpException
	 */
	public function actionOrgContact($orgId) {
		$model = new OrgContact();

		if (!($organization = Organization::findOne(['id' => $orgId]))) {
			throw new NotFoundHttpException();
		}

		if ($model->load(Yii::$app->request->post())) {
			if ($model->signup()) {
				Yii::$app->session->setFlash('error', 'Sikeres üzenetküldés!');
				return $this->redirect(Url::to('/'));
			}
		}

		$model->orgId = $orgId;

		Yii::$app->view->params['pageClass'] = 'orgContact';

		return $this->render('orgContact', [
			'model' => $model,
			'organization' => $organization
		]);
	}


}
