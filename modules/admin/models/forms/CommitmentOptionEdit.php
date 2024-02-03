<?php

namespace app\modules\admin\models\forms;

use app\models\CommitmentOption;
use Exception;
use Yii;

/**
 * Class CommitmentOptionEdit
 *
 * CommitmentOptionEdit form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentOptionEdit extends CommitmentOptionCreate {

	private ?CommitmentOption $commitmentOption = null;


	/**
	 * @param CommitmentOption $commitmentOption
	 */
	public function loadCommitmentOption(CommitmentOption $commitmentOption): void {
		$this->commitmentOption = $commitmentOption;
		$this->name = $commitmentOption->name;
		$this->order = $commitmentOption->order;
		$this->isCustomInput = $commitmentOption->isCustomInput;
		$this->description = $commitmentOption->description;
		$this->score = $commitmentOption->score;
	}


	/**
	 * Signs commitmentOption up.
	 *
	 * @return CommitmentOption|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function edit(): ?CommitmentOption {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$this->commitmentOption->name = $this->name;
			$this->commitmentOption->description = $this->description;
			$this->commitmentOption->order = $this->order;
			$this->commitmentOption->isCustomInput = $this->isCustomInput;
			$this->commitmentOption->score = $this->score;
			$success &= $this->commitmentOption->save();

			$transaction->commit();

			return $success ? $this->commitmentOption : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}
