<?php

namespace app\models\traits;

use ReflectionClass;
use yii\db\ActiveQuery;

/**
 * Trait WithCategoryTrait
 * @package app\models\traits
 */
trait WithCategoryTrait {


	/**
	 * @return ActiveQuery
	 * @throws \ReflectionException
	 */
	public function getCategory() {
		$reflectionClass = new ReflectionClass($this);
		$className = $reflectionClass->getName().'Option';
		$slug = strtolower($reflectionClass->getShortName());
		return $this->hasOne($className, ['id' => $slug.'_category_id']);
	}


}