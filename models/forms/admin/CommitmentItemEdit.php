<?php

namespace app\models\forms\admin;

use app\models\CommitmentItem;
use Exception;
use Yii;

/**
 * Class CommitmentItemEdit
 *
 * CommitmentItemEdit form
 *
 * @package app\models\forms\admin
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentItemEdit extends CommitmentItemCreate {

	/**
	 * @var CommitmentItem|mixed
	 */
	private $commitmentItem;


	/**
	 * @param CommitmentItem $commitmentItem
	 */
	public function loadCommitmentItem(CommitmentItem $commitmentItem) {
		$this->commitmentItem = $commitmentItem;
		$this->name = $commitmentItem->name;
		$this->order = $commitmentItem->order;
		$this->description = $commitmentItem->description;
		$this->monthStep = $commitmentItem->month_step;
		$this->monthsMin = $commitmentItem->months_min;
		$this->monthsMax = $commitmentItem->months_max;
	}


	/**
	 * Signs commitmentItem up.
	 *
	 * @return CommitmentItem|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function edit(): ?CommitmentItem {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$this->commitmentItem->name = $this->name;
			$this->commitmentItem->description = $this->description;
			$this->commitmentItem->order = $this->order;
			$this->commitmentItem->month_step = $this->monthStep;
			$this->commitmentItem->months_max = $this->monthsMax;
			$this->commitmentItem->months_min = $this->monthsMin;
			$success &= $this->commitmentItem->save();

			$transaction->commit();

			return $success ? $this->commitmentItem : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}