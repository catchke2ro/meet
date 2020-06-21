<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserCommitmentOption
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                     $id
 * @property int                     $user_commitment_fill_id
 * @property int                     $commitment_option_id
 * @property int|null                $instance_id
 * @property string                  $custom_input
 * @property UserCommitmentFill      $userCommitmentFill
 * @property CommitmentInstance|null $commitmentInstance
 * @property CommitmentOption        $option
 * @property int                     $months
 */
class UserCommitmentOption extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{user_commitment_options}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getUserCommitmentFill() {
		return $this->hasOne(UserCommitmentFill::class, ['id' => 'user_commitment_fill_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getCommitmentInstance() {
		return $this->hasOne(CommitmentInstance::class, ['id' => 'instance_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOption() {
		return $this->hasOne(CommitmentOption::class, ['id' => 'commitment_option_id']);
	}


}
