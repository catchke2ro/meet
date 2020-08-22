<?php

namespace meetbase\models;

use meetbase\models\interfaces\CategoryInterface;
use meetbase\models\traits\CategoryInstanceTrait;
use meetbase\models\traits\SharedModelTrait;
use meetbase\models\traits\WithItemsTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class QuestionCategory
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int                             $id
 * @property string                          $name
 * @property int                             $order
 * @property int                             $org_type_id
 * @property string                          $description
 * @property bool                            $has_instances
 * @property array|QuestionItem[]            items
 * @property QuestionOption                  $conditionOption
 * @property array|QuestionCategoryOrgType[] $orgTypes
 */
abstract class QuestionCategory extends ActiveRecord implements CategoryInterface {

	use CategoryInstanceTrait;
	use WithItemsTrait;
	use SharedModelTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'meet_question_categories';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getConditionOption() {
		return $this->hasOne($this->getModelClass(QuestionOption::class), ['id' => 'condition_question_option_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrgTypes() {
		return $this->hasMany($this->getModelClass(QuestionCategoryOrgType::class), ['question_category_id' => 'id']);
	}


}
