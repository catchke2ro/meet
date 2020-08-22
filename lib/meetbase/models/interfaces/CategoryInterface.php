<?php

namespace meetbase\models\interfaces;

use yii\db\ActiveQuery;
use yii\web\Request;

/**
 * Interface CategoryInterface
 * @package meetbase\models\interfaces
 */
interface CategoryInterface {


	/**
	 * @return ActiveQuery
	 */
	public function getItems();


	/**
	 * @param Request $request
	 *
	 * @return int
	 */
	public function getInstanceCount(Request $request);


}