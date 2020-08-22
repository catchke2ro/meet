<?php

namespace meetbase\models\traits;

use meetbase\models\QuestionOption;
use yii\web\Request;

/**
 * Trait CategoryInstanceTrait
 * @package meetbase\models\traits
 */
trait CategoryInstanceTrait {


	/**
	 * @param Request $request
	 *
	 * @return int
	 */
	public function getInstanceCount(Request $request) {
		$count = 1;
		if ($request->isPost &&
			$this->has_instances &&
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