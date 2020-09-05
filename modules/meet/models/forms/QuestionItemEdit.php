<?php

namespace app\modules\meet\models\forms;

use app\modules\meet\models\QuestionItem;
use Exception;
use Yii;

/**
 * Class QuestionItemEdit
 *
 * QuestionItemEdit form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionItemEdit extends QuestionItemCreate {

	/**
	 * @var QuestionItem|mixed
	 */
	private $questionItem;


	/**
	 * @param QuestionItem $questionItem
	 */
	public function loadQuestionItem(QuestionItem $questionItem) {
		$this->questionItem = $questionItem;
		$this->name = $questionItem->name;
		$this->order = $questionItem->order;
		$this->isActive = $questionItem->is_active;
		$this->description = $questionItem->description;
	}


	/**
	 * Signs questionItem up.
	 *
	 * @return QuestionItem|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function edit(): ?QuestionItem {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$this->questionItem->name = $this->name;
			$this->questionItem->description = $this->description;
			$this->questionItem->order = $this->order;
			$this->questionItem->is_active = $this->isActive;
			$success &= $this->questionItem->save();

			$transaction->commit();

			return $success ? $this->questionItem : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}