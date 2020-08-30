<?php

namespace meetbase\models;

use app\models\lutheran\Event;
use meetbase\models\interfaces\FillInterface;
use meetbase\models\interfaces\ItemInterface;
use DateTime;
use meetbase\models\lutheran\Organization;
use meetbase\models\traits\SharedModelTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class OrgCommitmentFill
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                   $id
 * @property int                   $org_id
 * @property int                   $target_module_id
 * @property int                   $manual_module_id
 * @property int                   $manual_score
 * @property string                $comment
 * @property int                   $org_type
 * @property string                $date
 * @property Organization          $organization
 * @property Module|null           $targetModule
 * @property Module|null           $manualModule
 * @property OrgCommitmentOption[] $options
 */
abstract class OrgCommitmentFill extends ActiveRecord implements FillInterface {

	use SharedModelTrait;

	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'].'org_commitment_fills';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrganization() {
		return $this->hasOne($this->getModelClass(Organization::class), ['id' => 'org_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getTargetModule() {
		return $this->hasOne($this->getModelClass(Module::class), ['id' => 'target_module_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getManualModule() {
		return $this->hasOne($this->getModelClass(Module::class), ['id' => 'manual_module_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOptions() {
		return $this->hasMany($this->getModelClass(OrgCommitmentOption::class), ['org_commitment_fill_id' => 'id']);
	}


	/**
	 * @return array|null
	 * @throws \yii\db\Exception
	 */
	public function getCheckedCommitmentOptions(): ?array {
		$questionOptionIds = [];
		/** @var OrgCommitmentOption $option */
		foreach ($this->getOptions()->all() as $option) {
			$questionOptionIds[] = $option->commitment_option_id;
		}

		return $questionOptionIds ?: null;
	}


	/**
	 * @param CommitmentCategory $commitmentCategory
	 *
	 * @return mixed|void
	 */
	public function getInstanceCountForCategory(CommitmentCategory $commitmentCategory) {
		$instNumber = (int) $this->getOptions()
			->innerJoinWith('commitmentOption as option')
			->innerJoinWith('commitmentOption.item as commitment')
			->where(['commitment.commitment_category_id' => $commitmentCategory->id])
			->count('DISTINCT `instance_id`');

		return $instNumber ?: 1;
	}


	/**
	 * @param CommitmentCategory $commitmentCategory
	 * @param int                $instanceNum
	 *
	 * @return QuestionInstance|null
	 */
	public function getInstance(CommitmentCategory $commitmentCategory, int $instanceNum): ?ActiveRecord {
		$instance = null;
		$instances = $this->getModelClass(CommitmentInstance::class)::find()
			->innerJoinWith('orgCommitmentOptions as orgCommitmentOptions')
			->innerJoinWith('orgCommitmentOptions.orgCommitmentFill as commitmentFill')
			->where(['commitmentFill.id' => $this->id, 'commitment_category_id' => $commitmentCategory->id])
			->all();
		if ($instances && isset($instances[$instanceNum])) {
			$instance = $instances[$instanceNum];
		}

		return $instance;
	}


	/**
	 * @param ItemInterface $item
	 *
	 * @return string|null
	 */
	public function getCustomInputValue(ItemInterface $item): ?string {
		/** @var OrgCommitmentOption|null $option */
		$option = $this->getOptions()
			->innerJoinWith('commitmentOption as option')
			->where(['option.is_custom_input' => 1, 'option.commitment_id' => $item->id])
			->one();

		return $option ? $option->custom_input : null;
	}


	/**
	 * @param CommitmentItem $commitment
	 * @param int            $instance
	 *
	 * @return int
	 */
	public function getIntervalValue(CommitmentItem $commitment, int $instance): int {
		$request = Yii::$app->request;
		$value = $commitment->months_min;
		if ($request->isPost &&
			!empty($request->getBodyParam('intervals')) &&
			!empty($request->getBodyParam('options')[$this->id][$instance])
		) {
			$value = (int) $request->getBodyParam('options')[$this->id][$instance];
		}

		/** @var OrgCommitmentOption $fillOptionOfCommitment */
		$fillOptionOfCommitment = $this->getOptions()
			->alias('fillOption')
			->innerJoinWith('commitmentOption as commitmentOption')
			->where(['commitmentOption.commitment_id' => $commitment->id])
			->one();
		if ($fillOptionOfCommitment && $fillOptionOfCommitment->months) {
			return $fillOptionOfCommitment->months;
		}

		return $value;
	}


	/**
	 * @return int
	 */
	public function getScore(): int {
		$score = 0;
		foreach ($this->options as $option) {
			$score += $option->commitmentOption ? $option->commitmentOption->score : 0;
		}

		return $score;
	}


	/**
	 * @return Module|null
	 */
	public function getFinalModule(): ?Module {
		if (!$this->isApproved()) {
			return null;
		}

		return $this->manualModule ?: $this->targetModule;
	}


	/**
	 * @return bool
	 */
	public function isApproved(): bool {
		return !is_null(Event::find()
			->andWhere(['ref1_id' => $this->id])
			->andWhere(['ref_tipus_id' => Yii::$app->params['event_type_meet_commitment_approved']])
			->andWhere(['ertek1' => 1])
			->one());
	}


}
