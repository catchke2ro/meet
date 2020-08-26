<?php

namespace app\modules\meet\controllers;

/**
 * Class IndexController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class IndexController extends AbstractAdminController {


	/**
	 * @return string
	 */
	public function actionIndex() {
		return $this->render('index', []);
	}


}
