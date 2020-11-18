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
	public function beforeAction($action) {
		if ($this->request->getMethod() === 'POST' && !empty($this->request->getBodyParam('admin_org_type'))) {
			Yii::$app->session->set('admin_org_type', $this->request->getBodyParam('admin_org_type'));
			$this->refresh();
			return false;
		}
		return parent::beforeAction($action);
	}


}
