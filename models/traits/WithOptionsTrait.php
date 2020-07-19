<?php

namespace app\models\traits;

use app\models\interfaces\FillInterface;
use ReflectionClass;
use yii\db\ActiveQuery;
use yii\web\Request;

/**
 * Trait WithOptionsTrait
 * @package app\models\traits
 */
trait WithOptionsTrait {


	/**
	 * Returns true if question/commitment has only one input, which is a custom text
	 */
	public function isOnlyCustomInput() {
		$hasCustomInput = $hasOtherInput = false;
		foreach ($this->options as $option) {
			$hasCustomInput |= $option->is_custom_input;
			$hasOtherInput |= !$option->is_custom_input;
		}

		return $hasCustomInput && !$hasOtherInput;
	}


	/**
	 * @param Request       $request
	 *
	 * @param FillInterface $fill
	 *
	 * @return string
	 */
	public function getCustomInputValue(Request $request, FillInterface $fill = null): string {
		if ($request->isPost &&
			!empty($request->getBodyParam('options')) &&
			!empty($request->getBodyParam('options')[$this->id]['__cI'])
		) {
			return $request->getBodyParam('options')[$this->id]['__cI'];
		}

		if (!is_null($fill)) {
			return $fill->getCustomInputValue($this) ?: '';
		}

		return '';
	}


	/**
	 * @return ActiveQuery
	 * @throws \ReflectionException
	 */
	public function getOptions() {
		$reflectionClass = new ReflectionClass($this);
		$className = str_replace('Item', 'Option', $reflectionClass->getName());
		$slug = strtolower(str_replace('Item', '', $reflectionClass->getShortName()));
		return $this->hasMany($className, [$slug.'_id' => 'id']);
	}


}