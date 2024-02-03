<?php

namespace app\modules\admin\controllers;

use app\models\User;
use app\modules\admin\models\forms\UserEdit;
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
	 * @return Response|string
	 */
	public function actionIndex(): Response|string {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(User::class);
		}

		return $this->render('index', []);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionEdit(int $id): Response|string {
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
			'model' => $model
		]);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 * @throws Throwable
	 * @throws StaleObjectException
	 */
	public function actionDelete(int $id): Response|string {
		/** @var User $user */
		if (!($user = User::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$user->delete();

		Yii::$app->session->setFlash('success', 'Modul sikeresen törölve');

		return $this->redirect(Url::to('/admin/users'));
	}


}
