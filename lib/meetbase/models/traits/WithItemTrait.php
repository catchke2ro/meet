<?php

namespace meetbase\models\traits;

use ReflectionClass;
use yii\db\ActiveQuery;

/**
 * Trait WithItemTrait
 * @package app\models\traits
 */
trait WithItemTrait {


	/**
	 * @return ActiveQuery
	 * @throws \ReflectionException
	 */
	public function getItem() {
		$reflectionClass = new ReflectionClass($this);
		$className = str_replace('Option', 'Item', $reflectionClass->getName());
		$slug = strtolower(str_replace('Option', '', $reflectionClass->getShortName()));

		return $this->hasOne($className, ['id' => $slug . '_id']);
	}


}