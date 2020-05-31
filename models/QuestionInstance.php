<?php

namespace app\models;

use app\models\traits\WithItemTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class QuestionInstance
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int              $id
 * @property string           $name
 * @property int              $question_category_id
 * @property QuestionCategory $questionCategory
 */
class QuestionInstance extends ActiveRecord {

	use WithItemTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{user_question_answer_instances}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestionCategory() {
		return $this->hasOne(QuestionCategory::class, ['id' => 'question_category_id']);
	}

}
