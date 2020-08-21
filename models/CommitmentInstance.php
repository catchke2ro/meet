<?php

namespace app\models;

use app\models\traits\WithItemTrait;
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
 * @property UserCommitmentOption $userCommitmentOptions
 */
class CommitmentInstance extends ActiveRecord {

	use WithItemTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{meet_user_commitment_option_instances}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getCommitmentCategory() {
		return $this->hasOne(CommitmentCategory::class, ['id' => 'commitment_category_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getUserCommitmentOptions() {
		return $this->hasMany(UserCommitmentOption::class, ['instance_id' => 'id']);
	}


}
