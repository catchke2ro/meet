<?php

namespace app\modules\admin\models\forms;

use app\models\CommitmentCategory;
use app\models\CommitmentCategoryOrgType;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;

/**
 * Class CommitmentCategoryEdit
 *
 * CommitmentCategoryEdit form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentCategoryEdit extends CommitmentCategoryCreate {

	private ?CommitmentCategory $commitmentCategory = null;


	/**
	 * @param CommitmentCategory $commitmentCategory
	 */
	public function loadCommitmentCategory(CommitmentCategory $commitmentCategory): void {
		$this->commitmentCategory = $commitmentCategory;
		$this->name = $commitmentCategory->name;
		$this->order = $commitmentCategory->order;
		$this->hasInstances = $commitmentCategory->hasInstances;
		$this->description = $commitmentCategory->description;
		$this->questionCategoryInstId = $commitmentCategory->questionCategoryInstId;
		$this->orgTypes = array_map(function (CommitmentCategoryOrgType $orgType) {
			return $orgType->orgTypeId;
		}, $commitmentCategory->orgTypes);
	}


	/**
	 * Signs commitmentCategory up.
	 *
	 * @return CommitmentCategory|null the saved model or null if saving fails
	 * @throws Throwable
	 * @throws \yii\db\Exception
	 * @throws StaleObjectException
	 */
	public function edit(): ?CommitmentCategory {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$this->commitmentCategory->name = $this->name;
			$this->commitmentCategory->description = $this->description;
			$this->commitmentCategory->hasInstances = $this->hasInstances;
			$this->commitmentCategory->order = $this->order;
			$this->commitmentCategory->questionCategoryInstId = $this->questionCategoryInstId;
			$success &= $this->commitmentCategory->save();

			$orgTypes = is_array($this->orgTypes) ? $this->orgTypes : [];
			$existingOrgTypes = [];
			foreach ($this->commitmentCategory->orgTypes as $orgType) {
				if (!in_array($orgType->orgTypeId, $orgTypes)) {
					$orgType->delete();
				} else {
					$existingOrgTypes[] = $orgType->orgTypeId;
				}
			}
			$newOrgTypes = array_diff($orgTypes, $existingOrgTypes);
			foreach ($newOrgTypes as $newOrgType) {
				$commitmentCategoryOrgType = new CommitmentCategoryOrgType();
				$commitmentCategoryOrgType->orgTypeId = $newOrgType;
				$commitmentCategoryOrgType->commitmentCategoryId = $this->commitmentCategory->id;
				$success &= $commitmentCategoryOrgType->save();
			}

			$transaction->commit();

			return $success ? $this->commitmentCategory : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}
