<?php

namespace app\modules\meet\controllers;

use app\models\lutheran\Organization;
use app\modules\meet\models\forms\UserEdit;
use app\models\User;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class UsersController
 *
 * User CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class UsersController extends AbstractAdminController {


	/**
	 * @return string
	 */
	public function actionIndex() {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(User::class);
		}

		return $this->render('index', []);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionEdit($id) {
		if (!($user = User::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new UserEdit();
		$model->loadUser($user);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($user = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Felhasználó sikeresen módosítva');

				return $this->redirect(Url::to('/meet/users'));
			}
		}
		Yii::$app->view->params['pageClass'] = 'userEdit';

		$orgList = Organization::getList(null, Yii::$app->params['registration_org_types']);

		return $this->render('edit', [
			'model'    => $model,
			'orgList'  => $orgList
		]);
	}


	/**
	 * @param $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 * @throws Throwable
	 * @throws StaleObjectException
	 */
	public function actionDelete($id) {
		/** @var User $user */
		if (!($user = User::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		foreach ($user->orgTypes as $orgType) {
			$orgType->delete();
		}
		$user->delete();

		Yii::$app->session->setFlash('success', 'Modul sikeresen törölve');

		return $this->redirect(Url::to('/meet/users'));
	}


}
