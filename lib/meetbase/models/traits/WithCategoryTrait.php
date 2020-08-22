<?php

namespace meetbase\models\traits;

use ReflectionClass;
use yii\db\ActiveQuery;

/**
 * Trait WithCategoryTrait
 * @package meetbase\models\traits
 */
trait WithCategoryTrait {


	/**
	 * @return ActiveQuery
	 * @throws \ReflectionException
	 */
	public function getCategory() {
		$reflectionClass = new ReflectionClass($this);
		$className = str_replace('Item', 'Category', $reflectionClass->getName());
		$slug = strtolower(str_replace('Item', '', $reflectionClass->getShortName()));
		return $this->hasOne($className, ['id' => $slug.'_category_id']);
	}


}