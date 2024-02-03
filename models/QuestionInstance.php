<?php

namespace app\models;

use app\models\traits\WithItemTrait;
use yii\db\ActiveQuery;

/**
 * Class QuestionInstance
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int              $id
 * @property string           $name
 * @property int              $questionCategoryId
 * @property QuestionCategory $questionCategory
 */
class QuestionInstance extends BaseModel {

	use WithItemTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'org_question_answer_instances';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestionCategory(): ActiveQuery {
		return $this->hasOne(QuestionCategory::class, ['id' => 'question_category_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestionAnswers(): ActiveQuery {
		return $this->hasMany(OrgQuestionAnswer::class, ['instance_id' => 'id']);
	}


}
