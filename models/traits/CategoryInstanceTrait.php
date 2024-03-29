<?php

namespace app\models\traits;

use yii\web\Request;

/**
 * Trait CategoryInstanceTrait
 * @package app\models\traits
 */
trait CategoryInstanceTrait {


	/**
	 * @param Request $request
	 *
	 * @return int
	 */
	public function getInstanceCount(Request $request): int {
		$count = 1;
		if ($request->isPost &&
			$this->hasInstances &&
			!empty($request->getBodyParam('options'))
		) {
			foreach ($this->items as $question) {
				if (!empty($request->getBodyParam('options')[$question->id])) {
					$count = max($count, max(array_map(function ($optionValues) {
						return is_array($optionValues) ? max(array_keys($optionValues)) + 1 : null;
					}, $request->getBodyParam('options')[$question->id])));
				}
			}
		}

		return $count;
	}


}
