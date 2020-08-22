<?php

namespace meetbase\models;

use meetbase\models\traits\SharedModelTrait;
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
abstract class UserQuestionAnswer extends ActiveRecord {

	use SharedModelTrait;

	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'meet_user_question_answers';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getUserQuestionFill() {
		return $this->hasOne($this->getModelClass(UserQuestionFill::class), ['id' => 'user_question_fill_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestionInstance() {
		return $this->hasOne($this->getModelClass(QuestionInstance::class), ['id' => 'instance_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOption() {
		return $this->hasOne($this->getModelClass(QuestionOption::class), ['id' => 'question_option_id']);
	}


}
