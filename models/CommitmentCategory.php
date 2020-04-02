<?php

namespace app\models;

use app\models\interfaces\CategoryInterface;
use app\models\traits\CategoryInstanceTrait;
use app\models\traits\WithItemsTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class CommitmentCategory
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int                    $id
 * @property string                 $name
 * @property int                    $order
 * @property string                 $description
 * @property bool                   $has_instances
 * @property array|CommitmentItem[] $items
 * @property CommitmentOption       $conditionOption
 */
class CommitmentCategory extends ActiveRecord implements CategoryInterface {

	use CategoryInstanceTrait;
	use WithItemsTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{commitment_categories}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getConditionOption() {
		return $this->hasOne(CommitmentOption::class, ['id' => 'condition_commitment_option_id']);
	}


}
