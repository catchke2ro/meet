<?php

namespace meetbase\models;

use meetbase\models\interfaces\CategoryInterface;
use meetbase\models\traits\CategoryInstanceTrait;
use meetbase\models\traits\SharedModelTrait;
use meetbase\models\traits\WithItemsTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class CommitmentCategory
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int                             $id
 * @property string                          $name
 * @property int                             $order
 * @property int                             $org_type_id
 * @property string                          $description
 * @property bool                            $has_instances
 * @property array|CommitmentItem[]          $items
 * @property CommitmentOption                $conditionOption
 * @property bool                            $special_points
 * @property int|null                        $question_category_inst_id
 * @property QuestionCategory                $questionCategoryInst
 * @property array|QuestionCategoryOrgType[] $orgTypes
 */
abstract class CommitmentCategory extends ActiveRecord implements CategoryInterface {

	use CategoryInstanceTrait;
	use WithItemsTrait;
	use SharedModelTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'].'commitment_categories';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getConditionOption() {
		return $this->hasOne($this->getModelClass(CommitmentOption::class), ['id' => 'condition_commitment_option_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestionCategoryInst() {
		return $this->hasOne($this->getModelClass(QuestionCategory::class), ['question_category_inst_id' => 'id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrgTypes() {
		return $this->hasMany($this->getModelClass(CommitmentCategoryOrgType::class), ['commitment_category_id' => 'id']);
	}


}
