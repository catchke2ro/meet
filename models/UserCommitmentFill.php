<?php

namespace app\models;

use app\lib\OrgTypes;
use app\models\interfaces\DataTableModelInterface;
use app\models\interfaces\FillInterface;
use app\models\interfaces\ItemInterface;
use DateTime;
use PDO;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserCommitmentFill
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                    $id
 * @property int                    $user_id
 * @property int                    $target_module_id
 * @property int                    $manual_module_id
 * @property int                    $manual_score
 * @property string                 $comment
 * @property bool                   $approved
 * @property int                    $org_type
 * @property DateTime               $date
 * @property User                   $user
 * @property Module|null            $targetModule
 * @property Module|null            $manualModule
 * @property UserCommitmentOption[] $options
 */
class UserCommitmentFill extends ActiveRecord implements FillInterface, DataTableModelInterface {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{meet_user_commitment_fills}}';
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
	public function getTargetModule() {
		return $this->hasOne(Module::class, ['id' => 'target_module_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOptions() {
		return $this->hasMany(UserCommitmentOption::class, ['user_commitment_fill_id' => 'id']);
	}


	/**
	 * @return array|null
	 * @throws \yii\db\Exception
	 */
	public function getCheckedCommitmentOptions(): ?array {
		$questionOptionIds = [];
		/** @var UserCommitmentOption $option */
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
		$instances = CommitmentInstance::find()
			->innerJoinWith('userCommitmentOptions as userCommitmentOptions')
			->innerJoinWith('userCommitmentOptions.userCommitmentFill as commitmentFill')
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
		/** @var UserCommitmentOption|null $option */
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

		/** @var UserCommitmentOption $fillOptionOfCommitment */
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
	 * @return array
	 */
	public function toDataTableArray(): array {
		return [
			'id'           => $this->id,
			'date'         => (new DateTime($this->date))->format('Y. m. d. H:i:s'),
			'user'         => $this->user->name,
			'targetModule' => $this->targetModule ? $this->targetModule->name : null,
			'score'        => $this->getScore()
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'view' => '<a href="/admin/user-commitments/' . $this->id . '" class="fa fa-eye" title="Megtekintés, szerkesztés"></a>',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getTextSearchColumns(): array {
		return [
		];
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
