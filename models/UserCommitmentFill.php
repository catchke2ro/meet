<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserCommitmentFill
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                  $id
 * @property int                  $user_id
 * @property \DateTime            $date
 * @property User                 $user
 * @property UserCommitmentAnswer[] $answers
 */
class UserCommitmentFill extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{user_commitment_fills}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getUser() {
		return $this->hasOne(User::class, ['id' => 'user_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOptions() {
		return $this->hasMany(UserCommitmentOption::class, ['user_commitment_option_id' => 'id']);
	}


}
