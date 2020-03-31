<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\Request;

/**
 * Class QuestionCategory
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int              $id
 * @property string           $name
 * @property int              $order
 * @property string           $description
 * @property bool             $has_instances
 * @property array|Question[] $questions
 * @property QuestionOption   $conditionOption
 */
class QuestionCategory extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{question_categories}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestions() {
		return $this->hasMany(Question::class, ['question_category_id' => 'id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getConditionOption() {
		return $this->hasOne(QuestionOption::class, ['id' => 'condition_question_option_id']);
	}


	/**
	 * @param Request $request
	 *
	 * @return int
	 */
	public function getInstanceCount(Request $request) {
		$count = 1;
		if ($request->isPost &&
			$this->has_instances &&
			!empty($request->getBodyParam('options'))
		) {
			foreach ($this->questions as $question) {
				if (!empty($request->getBodyParam('options')[$question->id])) {
					$count = max($count, max(array_map(function ($optionValues) {
						return is_array($optionValues) ? max(array_keys($optionValues)) + 1 : null;
					}, $request->getBodyParam('options')[$question->id])));
				}
			}
		}

		return $count;
	}


}
