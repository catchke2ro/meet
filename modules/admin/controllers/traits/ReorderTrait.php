<?php

namespace app\modules\admin\controllers\traits;

use app\models\CommitmentCategory;
use app\models\CommitmentItem;
use app\models\CommitmentOption;

/**
 * Trait ReorderTrait
 */
trait ReorderTrait {


	/**
	 * @param CommitmentOption|CommitmentItem|CommitmentCategory $child
	 * @param CommitmentItem|CommitmentCategory|array            $parentOrSiblings
	 * @param string|null                                        $childrenField
	 * @param int                                                $direction
	 *
	 * @return void
	 */
	protected function doReorder(
		CommitmentOption|CommitmentItem|CommitmentCategory $child,
		CommitmentItem|CommitmentCategory|array $parentOrSiblings,
		?string $childrenField,
		int $direction
	): void {
		if (is_array($parentOrSiblings)) {
			$items = $parentOrSiblings;
		} else {
			$items = $parentOrSiblings->$childrenField;
		}
		$key = array_search($child->id, array_map(fn($option) => $option->id, $items));
		$prev = $items[$key - 1] ?? null;
		$next = $items[$key + 1] ?? null;

		if ($direction === 1 && $next) {
			$next->order = $child->order;
			$child->order ++;
			$next->save();
			$child->save();
			if (is_object($parentOrSiblings)) {
				$parentOrSiblings->organizeOrders();
			}
		}
		if ($direction === - 1 && $prev) {
			$prev->order = $child->order;
			$child->order --;
			$prev->save();
			$child->save();
			if (is_object($parentOrSiblings)) {
				$parentOrSiblings->organizeOrders();
			}
		}
	}


}
