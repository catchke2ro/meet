<?php

namespace app\controllers;

use Yii;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Class BaseController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class BaseController extends Controller {


	/**
	 * @param Action $action
	 *
	 * @return bool
	 * @throws BadRequestHttpException
	 */
	public function beforeAction($action): bool {
		$reload = false;
		if ($this->request->getMethod() === 'POST' && !is_null($this->request->getBodyParam('admin_org_type'))) {
			Yii::$app->session->set('admin_org_type', $this->request->getBodyParam('admin_org_type'));
			$reload = true;
		}
		if ($this->request->getMethod() === 'POST' && !is_null($this->request->getBodyParam('admin_active_module'))) {
			Yii::$app->session->set('admin_active_module', $this->request->getBodyParam('admin_active_module'));
			$reload = true;
		}
		if ($reload) {
			$this->refresh();

			return false;
		}

		return parent::beforeAction($action);
	}


}
