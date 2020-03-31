<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\Request;

/**
 * Class Question
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                    $id
 * @property string                 $name
 * @property int                    $order
 * @property string                 $description
 * @property array|QuestionOption[] $questionOptions
 * @property QuestionCategory       $category
 */
class Question extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{questions}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getCategory() {
		return $this->hasOne(QuestionCategory::class, ['id' => 'question_category_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestionOptions() {
		return $this->hasMany(QuestionOption::class, ['question_id' => 'id']);
	}


	/**
	 * Returns true if question has only one input, which is a custom text
	 */
	public function isOnlyCustomInput() {
		$hasCustomInput = $hasOtherInput = false;
		foreach ($this->questionOptions as $questionOption) {
			$hasCustomInput |= $questionOption->is_custom_input;
			$hasOtherInput |= !$questionOption->is_custom_input;
		}
		return $hasCustomInput && !$hasOtherInput;
	}


	/**
	 * @return string
	 */
	public function getCssClass(): string {
		$classes = [];
		if ($this->isOnlyCustomInput()) {
			$classes[] = 'onlyCustomInput';
		}
		return implode(' ', $classes);
	}


	/**
	 * @param Request $request
	 *
	 * @return string
	 */
	public function getCustomInputValue(Request $request): string {
		if ($request->isPost &&
			!empty($request->getBodyParam('options')) &&
			!empty($request->getBodyParam('options')[$this->id]['__cI'])
		) {
			return $request->getBodyParam('options')[$this->id]['__cI'];
		}
		return '';
	}


}
