<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * Class OrgQuestionAnswer
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @@property int $id
 * @property int                   $orgQuestionFillId
 * @property int                   $questionOptionId
 * @property int|null              $instanceId
 * @property string                $customInput
 * @property OrgQuestionFill       $orgQuestionFill
 * @property QuestionInstance|null $questionInstance
 * @property QuestionOption        $option
 */
class OrgQuestionAnswer extends BaseModel {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'org_question_answers';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrgQuestionFill(): ActiveQuery {
		return $this->hasOne(OrgQuestionFill::class, ['id' => 'org_question_fill_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestionInstance(): ActiveQuery {
		return $this->hasOne(QuestionInstance::class, ['id' => 'instance_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOption(): ActiveQuery {
		return $this->hasOne(QuestionOption::class, ['id' => 'question_option_id']);
	}


}
