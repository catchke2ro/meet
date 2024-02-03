<?php

namespace app\modules\admin\models\forms;

use app\models\CommitmentItem;
use Exception;
use Yii;

/**
 * Class CommitmentItemEdit
 *
 * CommitmentItemEdit form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentItemEdit extends CommitmentItemCreate {

	private ?CommitmentItem $commitmentItem = null;


	/**
	 * @param CommitmentItem $commitmentItem
	 */
	public function loadCommitmentItem(CommitmentItem $commitmentItem): void {
		$this->commitmentItem = $commitmentItem;
		$this->name = $commitmentItem->name;
		$this->order = $commitmentItem->order;
		$this->isActive = $commitmentItem->isActive;
		$this->description = $commitmentItem->description;
		$this->monthStep = $commitmentItem->monthStep;
		$this->monthsMin = $commitmentItem->monthsMin;
		$this->monthsMax = $commitmentItem->monthsMax;
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
			$this->commitmentItem->isActive = $this->isActive;
			$this->commitmentItem->monthStep = $this->monthStep;
			$this->commitmentItem->monthsMax = $this->monthsMax;
			$this->commitmentItem->monthsMin = $this->monthsMin;
			$success &= $this->commitmentItem->save();

			$transaction->commit();

			return $success ? $this->commitmentItem : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}
