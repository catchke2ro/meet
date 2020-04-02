<?php

namespace app\models\traits;

use ReflectionClass;
use yii\db\ActiveQuery;

/**
 * Trait WithItemsTrait
 * @package app\models\traits
 */
trait WithItemsTrait {


	/**
	 * @return ActiveQuery
	 * @throws \ReflectionException
	 */
	public function getItems() {
		$reflectionClass = new ReflectionClass($this);
		$className = str_replace('Category', 'Item', $reflectionClass->getName());
		$slug = strtolower(str_replace('Category', '', $reflectionClass->getShortName()));
		return $this->hasMany($className, [$slug.'_category_id' => 'id']);
	}


}