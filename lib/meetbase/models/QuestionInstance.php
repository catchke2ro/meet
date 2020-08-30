<?php

namespace meetbase\models;

use meetbase\models\traits\SharedModelTrait;
use meetbase\models\traits\WithItemTrait;
use Yii;
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
abstract class QuestionInstance extends ActiveRecord {

	use WithItemTrait;
	use SharedModelTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'].'org_question_answer_instances';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestionCategory() {
		return $this->hasOne($this->getModelClass(QuestionCategory::class), ['id' => 'question_category_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestionAnswers() {
		return $this->hasMany($this->getModelClass(OrgQuestionAnswer::class), ['instance_id' => 'id']);
	}


}
