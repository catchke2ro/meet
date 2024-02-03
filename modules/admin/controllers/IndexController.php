<?php

namespace app\modules\admin\controllers;

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
	public function actionIndex(): string {
		return $this->render('index', []);
	}


}
