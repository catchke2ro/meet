<?php

namespace meetbase\models;

use meetbase\models\traits\SharedModelTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class OrgQuestionAnswer
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                   $id
 * @property int                   $org_question_fill_id
 * @property int                   $question_option_id
 * @property int|null              $instance_id
 * @property string                $custom_input
 * @property OrgQuestionFill      $orgQuestionFill
 * @property QuestionInstance|null $questionInstance
 * @property QuestionOption        $option
 */
abstract class OrgQuestionAnswer extends ActiveRecord {

	use SharedModelTrait;

	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'meet_org_question_answers';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrgQuestionFill() {
		return $this->hasOne($this->getModelClass(OrgQuestionFill::class), ['id' => 'org_question_fill_id']);
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
