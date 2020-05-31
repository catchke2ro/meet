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
 * @property int                   $id
 * @property int                   $user_question_fill_id
 * @property int                   $question_option_id
 * @property int|null              $instance_id
 * @property string                $custom_input
 * @property UserQuestionFill      $userQuestionFill
 * @property QuestionInstance|null $questionInstance
 * @property QuestionOption        $option
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
	public function getQuestionInstance() {
		return $this->hasOne(QuestionInstance::class, ['id' => 'instance_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOption() {
		return $this->hasOne(QuestionOption::class, ['id' => 'question_option_id']);
	}


}
