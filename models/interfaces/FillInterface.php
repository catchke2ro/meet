<?php


namespace app\models\interfaces;


use app\models\CommitmentCategory;
use app\models\CommitmentInstance;
use app\models\CommitmentItem;
use app\models\QuestionInstance;
use Yii;
use yii\db\ActiveRecord;

/**
 * Interface FillInterface
 * @package app\models\interfaces
 */
interface FillInterface {


	/**
	 * @return array
	 */
	public function getCheckedCommitmentOptions(): ?array;


	/**
	 * @param CommitmentCategory $commitmentCategory
	 *
	 * @return mixed
	 */
	public function getInstanceCountForCategory(CommitmentCategory $commitmentCategory);


	/**
	 * @param CommitmentCategory $commitmentCategory
	 * @param int                $instanceNum
	 *
	 * @return QuestionInstance|CommitmentInstance|null
	 */
	public function getInstance(CommitmentCategory $commitmentCategory, int $instanceNum): ?ActiveRecord;


	/**
	 * @param ItemInterface $item
	 *
	 * @return string|null
	 */
	public function getCustomInputValue(ItemInterface $item): ?string;


	/**
	 * @param CommitmentItem $commitment
	 * @param int            $instanceNumber
	 *
	 * @return mixed
	 */
	public function getIntervalValue(CommitmentItem $commitment, int $instanceNumber): int;


}