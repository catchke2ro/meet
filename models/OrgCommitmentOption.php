<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * Class OrgCommitmentOption
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                     $id
 * @property int                     $orgCommitmentFillId
 * @property int                     $commitmentOptionId
 * @property int|null                $instanceId
 * @property string                  $customInput
 * @property OrgCommitmentFill       $orgCommitmentFill
 * @property CommitmentInstance|null $commitmentInstance
 * @property CommitmentOption        $commitmentOption
 * @property int                     $months
 */
class OrgCommitmentOption extends BaseModel {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'org_commitment_options';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrgCommitmentFill(): ActiveQuery {
		return $this->hasOne(OrgCommitmentFill::class, ['id' => 'org_commitment_fill_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getCommitmentInstance(): ActiveQuery {
		return $this->hasOne(CommitmentInstance::class, ['id' => 'instance_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getCommitmentOption(): ActiveQuery {
		return $this->hasOne(CommitmentOption::class, ['id' => 'commitment_option_id']);
	}


}
