<?php

namespace app\models\forms\admin;

use app\models\QuestionItem;
use Exception;
use Yii;

/**
 * Class QuestionItemEdit
 *
 * QuestionItemEdit form
 *
 * @package app\models\forms\admin
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
			$success &= $this->questionItem->save();

			$transaction->commit();

			return $success ? $this->questionItem : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}