<?php

namespace app\modules\admin\controllers;

use app\models\Module;
use app\models\OrganizationType;
use app\modules\admin\models\forms\ModuleCreate;
use app\modules\admin\models\forms\ModuleEdit;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class ModulesController
 *
 * Module CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class ModulesController extends AbstractAdminController {


	/**
	 * @return Response|string
	 */
	public function actionIndex(): Response|string {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(Module::class);
		}

		return $this->render('index', []);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionCreate(): Response|string {
		$model = new ModuleCreate();

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($module = $model->create())) {
				Yii::$app->session->setFlash('success', 'Modul sikeresen létrehozva');

				return $this->redirect(Url::to('/admin/modules'));
			}
		}

		Yii::$app->view->params['pageClass'] = 'moduleCreate';

		return $this->render('create', [
			'orgTypes' => OrganizationType::getList(),
			'model'    => $model,
		]);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionEdit(int $id): Response|string {
		if (!($module = Module::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new ModuleEdit();
		$model->loadModule($module);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($module = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Modul sikeresen módosítva');

				return $this->redirect(Url::to('/admin/modules'));
			}
		}
		Yii::$app->view->params['pageClass'] = 'moduleEdit';

		return $this->render('edit', [
			'orgTypes' => OrganizationType::getList(),
			'model'    => $model,
		]);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 * @throws StaleObjectException
	 * @throws Throwable
	 */
	public function actionDelete(int $id): Response|string {
		/** @var Module $module */
		if (!($module = Module::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$module->delete();

		Yii::$app->session->setFlash('success', 'Modul sikeresen törölve');

		return $this->redirect(Url::to('/admin/modules'));
	}


}
