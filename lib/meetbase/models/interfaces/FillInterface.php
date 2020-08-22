<?php


namespace meetbase\models\interfaces;


use meetbase\models\CommitmentCategory;
use meetbase\models\CommitmentInstance;
use meetbase\models\CommitmentItem;
use meetbase\models\QuestionInstance;
use yii\db\ActiveRecord;

/**
 * Interface FillInterface
 * @package meetbase\models\interfaces
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