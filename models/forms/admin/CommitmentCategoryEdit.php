<?php

namespace app\models\forms\admin;

use app\models\CommitmentCategory;
use app\models\CommitmentCategoryOrgType;
use Exception;
use Yii;

/**
 * Class CommitmentCategoryEdit
 *
 * CommitmentCategoryEdit form
 *
 * @package app\models\forms\admin
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentCategoryEdit extends CommitmentCategoryCreate {

	/**
	 * @var CommitmentCategory|mixed
	 */
	private $commitmentCategory;


	/**
	 * @param CommitmentCategory $commitmentCategory
	 */
	public function loadCommitmentCategory(CommitmentCategory $commitmentCategory) {
		$this->commitmentCategory = $commitmentCategory;
		$this->name = $commitmentCategory->name;
		$this->order = $commitmentCategory->order;
		$this->hasInstances = $commitmentCategory->has_instances;
		$this->description = $commitmentCategory->description;
		$this->questionCategoryInstId = $commitmentCategory->question_category_inst_id;
		$this->orgTypes = array_map(function (CommitmentCategoryOrgType $orgType) {
			return $orgType->org_type_id;
		}, $commitmentCategory->orgTypes);
	}


	/**
	 * Signs commitmentCategory up.
	 *
	 * @return CommitmentCategory|null the saved model or null if saving fails
	 * @throws Exception
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
			$this->commitmentCategory->has_instances = $this->hasInstances;
			$this->commitmentCategory->order = $this->order;
			$this->commitmentCategory->question_category_inst_id = $this->questionCategoryInstId;
			$success &= $this->commitmentCategory->save();

			$orgTypes = is_array($this->orgTypes) ? $this->orgTypes : [];
			$existingOrgTypes = [];
			foreach ($this->commitmentCategory->orgTypes as $orgType) {
				if (!in_array($orgType->org_type_id, $orgTypes)) {
					$orgType->delete();
				} else {
					$existingOrgTypes[] = $orgType->org_type_id;
				}
			}
			$newOrgTypes = array_diff($orgTypes, $existingOrgTypes);
			foreach ($newOrgTypes as $newOrgType) {
				$commitmentCategoryOrgType = new CommitmentCategoryOrgType();
				$commitmentCategoryOrgType->org_type_id = $newOrgType;
				$commitmentCategoryOrgType->commitment_category_id = $this->commitmentCategory->id;
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