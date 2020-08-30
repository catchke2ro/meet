<?php

namespace meetbase\models;

use meetbase\models\traits\SharedModelTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class OrgCommitmentOption
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                     $id
 * @property int                     $org_commitment_fill_id
 * @property int                     $commitment_option_id
 * @property int|null                $instance_id
 * @property string                  $custom_input
 * @property OrgCommitmentFill      $orgCommitmentFill
 * @property CommitmentInstance|null $commitmentInstance
 * @property CommitmentOption        $commitmentOption
 * @property int                     $months
 */
abstract class OrgCommitmentOption extends ActiveRecord {

	use SharedModelTrait;

	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'].'org_commitment_options';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrgCommitmentFill() {
		return $this->hasOne($this->getModelClass(OrgCommitmentFill::class), ['id' => 'org_commitment_fill_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getCommitmentInstance() {
		return $this->hasOne($this->getModelClass(CommitmentInstance::class), ['id' => 'instance_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getCommitmentOption() {
		return $this->hasOne($this->getModelClass(CommitmentOption::class), ['id' => 'commitment_option_id']);
	}


}
