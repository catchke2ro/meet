<?php

namespace app\controllers\admin;

use app\lib\OrgTypes;
use app\models\forms\admin\ModuleEdit;
use app\models\forms\admin\ModuleCreate;
use app\models\Module;
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
	 * @return string
	 */
	public function actionIndex() {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(Module::class);
		}

		return $this->render('index', []);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionCreate() {
		$model = new ModuleCreate();

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($module = $model->create())) {
				Yii::$app->session->setFlash('success', 'Modul sikeresen létrehozva');
				return $this->redirect(Url::to('/admin/modules'));
			}
		}

		Yii::$app->view->params['pageClass'] = 'moduleCreate';

		return $this->render('create', [
			'orgTypes' => OrgTypes::getInstance(),
			'model' => $model,
		]);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionEdit($id) {
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
			'orgTypes' => OrgTypes::getInstance(),
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
		/** @var Module $module */
		if (!($module = Module::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		foreach ($module->orgTypes as $orgType) {
			$orgType->delete();
		}
		$module->delete();

		Yii::$app->session->setFlash('success', 'Modul sikeresen törölve');
		return $this->redirect(Url::to('/admin/modules'));
	}


}
