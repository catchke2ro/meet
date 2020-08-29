<?php

namespace app\modules\meet\models\forms;

use app\modules\meet\models\QuestionCategory;
use Exception;
use meetbase\models\QuestionCategoryOrgType;
use Yii;

/**
 * Class QuestionCategoryEdit
 *
 * QuestionCategoryEdit form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionCategoryEdit extends QuestionCategoryCreate {

	/**
	 * @var QuestionCategory|mixed
	 */
	private $questionCategory;


	/**
	 * @param QuestionCategory $questionCategory
	 */
	public function loadQuestionCategory(QuestionCategory $questionCategory) {
		$this->questionCategory = $questionCategory;
		$this->name = $questionCategory->name;
		$this->order = $questionCategory->order;
		$this->hasInstances = $questionCategory->has_instances;
		$this->description = $questionCategory->description;
		$this->orgTypes = array_map(function(QuestionCategoryOrgType $orgType) {
			return $orgType->org_type_id;
		}, $questionCategory->orgTypes);
	}


	/**
	 * Signs questionCategory up.
	 *
	 * @return QuestionCategory|null the saved model or null if saving fails
	 * @throws Exception
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
			$this->questionCategory->has_instances = $this->hasInstances;
			$this->questionCategory->order = $this->order;
			$success &= $this->questionCategory->save();

			$orgTypes = is_array($this->orgTypes) ? $this->orgTypes : [];
			$existingOrgTypes = [];
			foreach ($this->questionCategory->orgTypes as $orgType) {
				if (!in_array($orgType->org_type_id, $orgTypes)) {
					$orgType->delete();
				} else {
					$existingOrgTypes[] = $orgType->org_type_id;
				}
			}
			$newOrgTypes = array_diff($orgTypes, $existingOrgTypes);
			foreach ($newOrgTypes as $newOrgType) {
				$questionCategoryOrgType = new \app\modules\meet\models\QuestionCategoryOrgType();
				$questionCategoryOrgType->org_type_id = $newOrgType;
				$questionCategoryOrgType->question_category_id = $this->questionCategory->id;
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