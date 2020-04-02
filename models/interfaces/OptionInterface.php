<?php

namespace app\models\interfaces;

use yii\db\ActiveQuery;
use yii\web\Request;

/**
 * Interface OptionInterface
 * @package app\models\interfaces
 */
interface OptionInterface {


	/**
	 * @return ActiveQuery
	 */
	public function getItem();


	/**
	 * @param Request $request
	 *
	 * @param int     $instance
	 *
	 * @return bool
	 */
	public function isChecked(Request $request, int $instance): bool;


}