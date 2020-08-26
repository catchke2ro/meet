<?php

namespace app\modules\meet\models\forms;

use app\modules\meet\models\QuestionOption;
use Exception;
use Yii;

/**
 * Class QuestionOptionEdit
 *
 * QuestionOptionEdit form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionOptionEdit extends QuestionOptionCreate {

	/**
	 * @var QuestionOption|mixed
	 */
	private $questionOption;


	/**
	 * @param QuestionOption $questionOption
	 */
	public function loadQuestionOption(QuestionOption $questionOption) {
		$this->questionOption = $questionOption;
		$this->name = $questionOption->name;
		$this->order = $questionOption->order;
		$this->isCustomInput = $questionOption->is_custom_input;
		$this->description = $questionOption->description;
	}


	/**
	 * Signs questionOption up.
	 *
	 * @return QuestionOption|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function edit(): ?QuestionOption {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$this->questionOption->name = $this->name;
			$this->questionOption->description = $this->description;
			$this->questionOption->order = $this->order;
			$this->questionOption->is_custom_input = $this->isCustomInput;
			$success &= $this->questionOption->save();

			$transaction->commit();

			return $success ? $this->questionOption : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}