<?php

namespace app\modules\admin\models\forms;

use app\models\QuestionCategory;
use app\models\QuestionCategoryOrgType;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;

/**
 * Class QuestionCategoryEdit
 *
 * QuestionCategoryEdit form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionCategoryEdit extends QuestionCategoryCreate {

	private ?QuestionCategory $questionCategory = null;


	/**
	 * @param QuestionCategory $questionCategory
	 */
	public function loadQuestionCategory(QuestionCategory $questionCategory): void {
		$this->questionCategory = $questionCategory;
		$this->name = $questionCategory->name;
		$this->order = $questionCategory->order;
		$this->hasInstances = $questionCategory->hasInstances;
		$this->description = $questionCategory->description;
		$this->orgTypes = array_map(function (QuestionCategoryOrgType $orgType) {
			return $orgType->orgTypeId;
		}, $questionCategory->orgTypes);
	}


	/**
	 * Signs questionCategory up.
	 *
	 * @return QuestionCategory|null the saved model or null if saving fails
	 * @throws Throwable
	 * @throws \yii\db\Exception
	 * @throws StaleObjectException
	 */
	public function edit(): ?QuestionCategory {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$this->questionCategory->name = $this->name;
			$this->questionCategory->description = $this->description;
			$this->questionCategory->hasInstances = $this->hasInstances;
			$this->questionCategory->order = $this->order;
			$success &= $this->questionCategory->save();

			$orgTypes = is_array($this->orgTypes) ? $this->orgTypes : [];
			$existingOrgTypes = [];
			foreach ($this->questionCategory->orgTypes as $orgType) {
				if (!in_array($orgType->orgTypeId, $orgTypes)) {
					$orgType->delete();
				} else {
					$existingOrgTypes[] = $orgType->orgTypeId;
				}
			}
			$newOrgTypes = array_diff($orgTypes, $existingOrgTypes);
			foreach ($newOrgTypes as $newOrgType) {
				$questionCategoryOrgType = new QuestionCategoryOrgType();
				$questionCategoryOrgType->orgTypeId = $newOrgType;
				$questionCategoryOrgType->questionCategoryId = $this->questionCategory->id;
				$success &= $questionCategoryOrgType->save();
			}

			$transaction->commit();

			return $success ? $this->questionCategory : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}
