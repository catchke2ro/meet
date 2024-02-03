<?php

namespace app\models;

use app\models\interfaces\FillInterface;
use app\models\interfaces\ItemInterface;
use PDO;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class OrgQuestionFill
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int                 $id
 * @property int                 $orgId
 * @property int                 $orgTypeId
 * @property \DateTime           $date
 * @property Organization        $organization
 * @property OrgQuestionAnswer[] $answers
 */
class OrgQuestionFill extends BaseModel implements FillInterface {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'org_question_fills';
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
	public function getAnswers(): ActiveQuery {
		return $this->hasMany(OrgQuestionAnswer::class, ['org_question_fill_id' => 'id']);
	}


	/**
	 * @param CommitmentCategory $commitmentCategory
	 *
	 * @return int
	 */
	public function getInstanceCountForCategory(CommitmentCategory $commitmentCategory): int {
		$instNumber = 1;
		if ($commitmentCategory->questionCategoryInstId) {
			$instNumber = (int) $this->getAnswers()
				->innerJoinWith('questionInstance as questionInstance')
				->where(['questionInstance.question_category_id' => $commitmentCategory->questionCategoryInstId])
				->count('DISTINCT `questionInstance`.`id`');
		}

		return $instNumber;
	}


	/**
	 * @param CommitmentCategory $commitmentCategory
	 * @param int                $instanceNum
	 *
	 * @return QuestionInstance|null
	 */
	public function getInstance(CommitmentCategory $commitmentCategory, int $instanceNum): ?ActiveRecord {
		$instance = null;
		if ($commitmentCategory->questionCategoryInstId) {
			$instances = QuestionInstance::class::find()
				->innerJoinWith('questionAnswers as questionAnswers')
				->innerJoinWith('questionAnswers.orgQuestionFill as questionFill')
				->where(['questionFill.id' => $this->id])
				->all();
			if ($instances && isset($instances[$instanceNum])) {
				$instance = $instances[$instanceNum];
			}
		}

		return $instance;
	}


	/**
	 * @return array|null
	 * @throws \yii\db\Exception
	 */
	public function getCheckedCommitmentOptions(): ?array {
		/** @var OrgQuestionAnswer $answer */
		$questionOptionIds = [];
		foreach ($this->getAnswers()->all() as $answer) {
			$questionOptionIds[] = $answer->questionOptionId;
		}

		$command = Yii::$app->db
			->createCommand('SELECT commitment_option_id FROM commitments_by_questions WHERE question_option_id IN (' . implode(',', $questionOptionIds) . ')');
		$checkedCommitmentOptions = $command->queryAll(PDO::FETCH_COLUMN);

		return $checkedCommitmentOptions ?: null;
	}


	/**
	 * @param ItemInterface $item
	 *
	 * @return string|null
	 */
	public function getCustomInputValue(ItemInterface $item): ?string {
		/** @var OrgQuestionAnswer|null $option */
		$option = $this->getAnswers()
			->innerJoinWith('option as option')
			->where(['option.is_custom_input' => 1, 'option.question_id' => $item->id])
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
			!empty($request->getBodyParam('options')[$commitment->id][$instanceNumber])
		) {
			$value = (int) $request->getBodyParam('options')[$commitment->id][$instanceNumber];
		}

		return $value;
	}


}
