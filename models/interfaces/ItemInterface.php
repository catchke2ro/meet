<?php

namespace app\models\interfaces;

use yii\db\ActiveQuery;
use yii\web\Request;

/**
 * Interface ItemInterface
 * @package app\models\interfaces
 */
interface ItemInterface {


	/**
	 * @return ActiveQuery
	 */
	public function getCategory(): ActiveQuery;


	/**
	 * @return string
	 */
	public function getCssClass(): string;


	/**
	 * Returns true if question/commitment has only one input, which is a custom text
	 */
	public function isOnlyCustomInput(): bool;


	/**
	 * @param Request $request
	 *
	 * @return string
	 */
	public function getCustomInputValue(Request $request): string;


	/**
	 * @return ActiveQuery
	 * @throws \ReflectionException
	 */
	public function getOptions(): ActiveQuery;


}