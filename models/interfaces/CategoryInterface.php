<?php

namespace app\models\interfaces;

use yii\db\ActiveQuery;
use yii\web\Request;

/**
 * Interface CategoryInterface
 * @package app\models\interfaces
 */
interface CategoryInterface {


	/**
	 * @return ActiveQuery
	 */
	public function getItems(): ActiveQuery;


	/**
	 * @param Request $request
	 *
	 * @return int
	 */
	public function getInstanceCount(Request $request): int;


}