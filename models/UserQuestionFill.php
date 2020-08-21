<?php

namespace app\models;

use app\models\interfaces\FillInterface;
use app\models\interfaces\ItemInterface;
use PDO;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\Request;

/**
 * Class UserQuestionFill
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                  $id
 * @property int                  $user_id
 * @property int                  $org_type
 * @property \DateTime            $date
 * @property User                 $user
 * @property UserQuestionAnswer[] $answers
 */
class UserQuestionFill extends ActiveRecord implements FillInterface {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{meet_user_question_fills}}';
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
	public function getAnswers() {
		return $this->hasMany(UserQuestionAnswer::class, ['user_question_fill_id' => 'id']);
	}


	/**
	 * @param CommitmentCategory $commitmentCategory
	 *
	 * @return int
	 */
	public function getInstanceCountForCategory(CommitmentCategory $commitmentCategory) {
		$instNumber = 1;
		if ($commitmentCategory->question_category_inst_id) {
			$instNumber = (int) $this->getAnswers()
				->innerJoinWith('questionInstance as questionInstance')
				->where(['questionInstance.question_category_id' => $commitmentCategory->question_category_inst_id])
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
		if ($commitmentCategory->question_category_inst_id) {
			$instances = QuestionInstance::find()
				->innerJoinWith('questionAnswers as questionAnswers')
				->innerJoinWith('questionAnswers.userQuestionFill as questionFill')
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
		/** @var UserQuestionAnswer $answer */
		$questionOptionIds = [];
		foreach ($this->getAnswers()->all() as $answer) {
			$questionOptionIds[] = $answer->question_option_id;
		}

		$command = Yii::$app->db
			->createCommand('SELECT commitment_option_id FROM meet_commitments_by_questions WHERE question_option_id IN ('.implode(',', $questionOptionIds).')');
		$checkedCommitmentOptions = $command->queryAll(PDO::FETCH_COLUMN);
		return $checkedCommitmentOptions ?: null;
	}


	/**
	 * @param ItemInterface $item
	 *
	 * @return string|null
	 */
	public function getCustomInputValue(ItemInterface $item): ?string {
		/** @var UserQuestionAnswer|null $option */
		$option = $this->getAnswers()
			->innerJoinWith('option as option')
			->where(['option.is_custom_input' => 1, 'option.question_id' => $item->id])
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
			!empty($request->getBodyParam('options')[$commitment->id][$instance])
		) {
			$value = (int) $request->getBodyParam('options')[$commitment->id][$instance];
		}
		return $value;
	}


}
