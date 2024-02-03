<?php

namespace app\modules\admin\models\forms;

use app\models\QuestionOption;
use Exception;
use Yii;

/**
 * Class QuestionOptionEdit
 *
 * QuestionOptionEdit form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionOptionEdit extends QuestionOptionCreate {

	private ?QuestionOption $questionOption = null;


	/**
	 * @param QuestionOption $questionOption
	 */
	public function loadQuestionOption(QuestionOption $questionOption): void {
		$this->questionOption = $questionOption;
		$this->name = $questionOption->name;
		$this->order = $questionOption->order;
		$this->isCustomInput = $questionOption->isCustomInput;
		$this->description = $questionOption->description;
		$this->commitmentOptions = $questionOption->commitmentOptions;
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
			$this->questionOption->isCustomInput = $this->isCustomInput;
			$success &= $this->questionOption->save();

			if (is_array($this->commitmentOptions)) {
				Yii::$app->db->createCommand()->delete('commitments_by_questions', [
					'question_option_id' => $this->questionOption->id,
				])->execute();
				foreach ($this->commitmentOptions as $commitmentOptionId) {
					Yii::$app->db->createCommand()->insert('commitments_by_questions', [
						'question_option_id'   => $this->questionOption->id,
						'commitment_option_id' => $commitmentOptionId
					])->execute();
				}
			}

			$transaction->commit();

			return $success ? $this->questionOption : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}
