<?php

namespace app\models\traits;

use ReflectionClass;
use ReflectionException;
use yii\db\ActiveQuery;

/**
 * Trait WithItemsTrait
 * @package app\models\traits
 */
trait WithItemsTrait {


	/**
	 * @return ActiveQuery
	 * @throws ReflectionException
	 */
	public function getItems(): ActiveQuery {
		$reflectionClass = new ReflectionClass($this);
		$className = str_replace('Category', 'Item', $reflectionClass->getName());
		$slug = strtolower(str_replace('Category', '', $reflectionClass->getShortName()));

		return $this->hasMany($className, [$slug . '_category_id' => 'id'])->orderBy(['order' => SORT_ASC]);
	}


	/**
	 * @return void
	 * @throws ReflectionException
	 */
	public function organizeOrders(): void {
		$subItems = $this->getItems()->orderBy(['order' => SORT_ASC, 'id' => SORT_DESC])->all();
		$i = 1;
		foreach ($subItems as $subItem) {
			$subItem->order = $i ++;
			$subItem->save();
		}
	}


}
