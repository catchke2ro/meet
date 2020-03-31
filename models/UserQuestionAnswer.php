<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserQuestionAnswer
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int              $id
 * @property int              $user_question_fill_id
 * @property int              $question_option_id
 * @property int              $instance_number
 * @property string           $custom_input
 * @property UserQuestionFill $userQuestionFille
 * @property QuestionOption   $option
 */
class UserQuestionAnswer extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{user_question_answers}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getUserQuestionFill() {
		return $this->hasOne(UserQuestionFill::class, ['id' => 'user_question_fill_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOption() {
		return $this->hasOne(QuestionOption::class, ['id' => 'question_option_id']);
	}


}
