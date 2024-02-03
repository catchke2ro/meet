<?php

namespace app\lib;

use app\models\CommitmentOption;
use app\models\interfaces\CategoryInterface;
use app\models\interfaces\ItemInterface;
use app\models\interfaces\OptionInterface;
use app\models\QuestionOption;
use yii\db\ActiveRecord;

/**
 * Class TreeLib
 *
 * @package app\lib
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class TreeLib {


	/**
	 * @param array|ActiveRecord[]|CategoryInterface[] $categories
	 *
	 * @return array
	 */
	public function populateTree(array $categories): array {
		/**
		 * @var $category ActiveRecord|CategoryInterface
		 * @var $item     ActiveRecord|ItemInterface
		 * @var $option   ActiveRecord|OptionInterface
		 */
		$categoriesByItems = [];
		foreach ($categories as $category) {
			foreach ($category->items as $item) {
				$categoriesByItems[$item->id] = $category->id;
				$item->populateRelation('category', $category);
				foreach ($item->options as $option) {
					switch (get_class($option)) {
						case QuestionOption::class:
							$option->populateRelation('question', $item);
							break;
						case CommitmentOption::class:
							$option->populateRelation('commitment', $item);
							break;
					}
				}
			}
		}

		return $categoriesByItems;
	}


}
