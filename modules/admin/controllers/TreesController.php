<?php

namespace app\modules\admin\controllers;

use app\models\CommitmentCategory;

/**
 * Class TreesController
 *
 * Post CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class TreesController extends AbstractAdminController {


	/**
	 * @return string
	 */
	public function actionIndex(): string {
		/** @var CommitmentCategory[] $categories */
		$categories = CommitmentCategory::find()->with(['items', 'items.options'])->orderBy('order ASC')->all();

		/*foreach ($categories as $category) {
			usort($category->items, function ($a, $b) {
				return $a->order <=> $b->order;
			});
			foreach ($category->items as $item) {
				usort($item->options, function ($a, $b) {
					return $a->order <=> $b->order;
				});
			}
		}*/

		$type = $this->request->get('type', 'commitment');
		$state = match ($type) {
			'commitment' => isset($_COOKIE['commitment-tree-state']) ? json_decode($_COOKIE['commitment-tree-state'], true) : [],
			'question' => isset($_COOKIE['question-tree-state']) ? json_decode($_COOKIE['question-tree-state'], true) : [],
			default => []
		};

		return $this->render('index', ['categories' => $categories, 'state' => $state]);
	}


}
