<?php

namespace app\controllers;

use app\models\lutheran\Organization;
use Yii;
use yii\web\Controller;

/**
 * Class AjaxController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class AjaxController extends Controller {


	/**
	 * Select2 organization search
	 *
	 * @param null $term
	 *
	 * @return string
	 */
	public function actionOrgs($term = null) {
		$orgs = [];
		if ($term && strlen($term) >= 2) {
			$orgs = Organization::getList($term, false);
			$orgs = array_map(function (Organization $organization) {
				return [
					'id'   => $organization->id,
					'text' => $organization->nev
				];
			}, $orgs);
		}

		array_unshift($orgs, ['id' => '', 'text' => ' - Nem szereplek az adatbázisban - ']);
		return $this->asJson([
			'results' => $orgs
		]);
	}


}
