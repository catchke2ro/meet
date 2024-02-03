<?php

namespace app\models;

use app\models\interfaces\DataTableModelInterface;
use app\models\interfaces\FillInterface;
use app\models\interfaces\ItemInterface;
use DateTime;
use Exception;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class OrgCommitmentFill
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                   $id
 * @property int                   $orgId
 * @property int                   $targetModuleId
 * @property int                   $manualModuleId
 * @property int                   $manualScore
 * @property string                $comment
 * @property int                   $orgTypeId
 * @property string                $date
 * @property bool                  $isApproved
 * @property Organization          $organization
 * @property Module|null           $targetModule
 * @property Module|null           $manualModule
 * @property OrgCommitmentOption[] $options
 */
class OrgCommitmentFill extends BaseModel implements FillInterface, DataTableModelInterface {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'org_commitment_fills';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrganization(): ActiveQuery {
		return $this->hasOne(Organization::class, ['id' => 'org_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getTargetModule(): ActiveQuery {
		return $this->hasOne(Module::class, ['id' => 'target_module_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getManualModule(): ActiveQuery {
		return $this->hasOne(Module::class, ['id' => 'manual_module_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOptions(): ActiveQuery {
		return $this->hasMany(OrgCommitmentOption::class, ['org_commitment_fill_id' => 'id']);
	}


	/**
	 * @return array|null
	 */
	public function getCheckedCommitmentOptions(): ?array {
		$questionOptionIds = [];
		/** @var OrgCommitmentOption $option */
		foreach ($this->getOptions()->all() as $option) {
			$questionOptionIds[] = $option->commitmentOptionId;
		}

		return $questionOptionIds ?: null;
	}


	/**
	 * @param CommitmentCategory $commitmentCategory
	 *
	 * @return int
	 */
	public function getInstanceCountForCategory(CommitmentCategory $commitmentCategory): int {
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
	public function getInstance(CommitmentCategory $commitmentCategory, int $instanceNum): ?CommitmentInstance {
		$instance = null;
		$instances = CommitmentInstance::class::find()
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

		return $option?->customInput;
	}


	/**
	 * @param CommitmentItem $commitment
	 * @param int            $instanceNumber
	 *
	 * @return int
	 */
	public function getIntervalValue(CommitmentItem $commitment, int $instanceNumber): int {
		$request = Yii::$app->request;
		$value = $commitment->monthsMin;
		if ($request->isPost &&
			!empty($request->getBodyParam('intervals')) &&
			!empty($request->getBodyParam('options')[$this->id][$instanceNumber])
		) {
			$value = (int) $request->getBodyParam('options')[$this->id][$instanceNumber];
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
		if (!$this->isApproved) {
			return null;
		}

		return $this->manualModule ?: $this->targetModule;
	}


	/**
	 * @return array
	 * @throws Exception
	 */
	public function toDataTableArray(): array {
		return [
			'id'           => $this->id,
			'date'         => (new DateTime($this->date))->format('Y. m. d. H:i:s'),
			'user'         => $this->organization->name,
			'targetModule' => $this->targetModule?->name,
			'score'        => $this->getScore()
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'view' => '<a href="/admin/org-commitments/' . $this->id . '" class="fa fa-eye" title="Megtekintés, szerkesztés"></a>',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getTextSearchColumns(): array {
		return [];
	}


	/**
	 * @return array|string[]
	 */
	public static function getOrderableColumns(): array {
		return [
			'id',
			'date',
			'user'
		];
	}


}
