<?php

namespace app\controllers\admin;

use app\models\forms\admin\UserEdit;
use app\models\forms\admin\UserCreate;
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
	public function actionCreate() {
		$model = new UserCreate();

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($user = $model->create())) {
				Yii::$app->session->setFlash('success', 'Felhasználó sikeresen létrehozva');
				return $this->redirect(Url::to('/admin/users'));
			}
		}

		Yii::$app->view->params['pageClass'] = 'userCreate';

		return $this->render('create', [
			'model' => $model,
		]);
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
				return $this->redirect(Url::to('/admin/users'));
			}
		}
		Yii::$app->view->params['pageClass'] = 'userEdit';

		return $this->render('edit', [
			'model' => $model,
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
		$user->delete();

		Yii::$app->session->setFlash('success', 'Felhasználó sikeresen törölve');
		return $this->redirect(Url::to('/admin/users'));
	}


}
