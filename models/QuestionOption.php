<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\Request;

/**
 * Class QuestionOption
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int      $id
 * @property string   $name
 * @property int      $order
 * @property bool     $is_custom_input
 * @property string   $description
 * @property Question $question
 */
class QuestionOption extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{question_options}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestion() {
		return $this->hasOne(Question::class, ['id' => 'question_id']);
	}


	/**
	 * @param Request $request
	 *
	 * @param int     $instance
	 *
	 * @return bool
	 */
	public function isChecked(Request $request, int $instance): bool {
		$checked = false;
		if ($this->question && $this->question->isOnlyCustomInput()) {
			$checked = true;
		}
		if ($request->isPost &&
			$this->question &&
			!empty($request->getBodyParam('options')) &&
			!empty($request->getBodyParam('options')[$this->question->id][$this->id][$instance])
		) {
			$checked = true;
		}
		return $checked;
	}


}
