<?php

namespace app\modules\meet\models;

use meetbase\models\traits\WithItemTrait;
use meetbase\models\traits\WithItemTrait\CommitmentInstance as BaseCommitmentInstance;
use yii\db\ActiveQuery;

/**
 * Class CommitmentInstance
 *
 * @package   app\modules\meet\models
 * @author    SRG Group <dev@srg.hu>
 * @copyright 2020 SRG Group Kft.
 */
class CommitmentInstance extends BaseCommitmentInstance {

	use WithItemTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'meet_user_commitment_option_instances';
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
