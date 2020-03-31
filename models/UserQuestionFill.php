<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserQuestionFill
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                  $id
 * @property int                  $user_id
 * @property \DateTime            $date
 * @property User                 $user
 * @property UserQuestionAnswer[] $answers
 */
class UserQuestionFill extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{user_question_fills}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getUser() {
		return $this->hasOne(User::class, ['id' => 'user_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getAnswers() {
		return $this->hasMany(UserQuestionAnswer::class, ['user_question_fill_id' => 'id']);
	}


}
