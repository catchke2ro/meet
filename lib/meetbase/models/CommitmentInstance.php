<?php

namespace meetbase\models;

use meetbase\models\CommitmentCategory;
use meetbase\models\traits\SharedModelTrait;
use meetbase\models\traits\WithItemTrait;
use meetbase\models\OrgCommitmentOption;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class CommitmentInstance
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int                  $id
 * @property string               $name
 * @property int                  $commitment_category_id
 * @property CommitmentCategory   $commitmentCategory
 * @property OrgCommitmentOption $orgCommitmentOptions
 */
abstract class CommitmentInstance extends ActiveRecord {

	use WithItemTrait;
	use SharedModelTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'meet_org_commitment_option_instances';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getCommitmentCategory() {
		return $this->hasOne($this->getModelClass(CommitmentCategory::class), ['id' => 'commitment_category_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrgCommitmentOptions() {
		return $this->hasMany($this->getModelClass(OrgCommitmentOption::class), ['instance_id' => 'id']);
	}


}
