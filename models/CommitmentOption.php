<?php

namespace app\models;

use app\models\traits\WithItemTrait;
use yii\db\ActiveRecord;
use yii\web\Request;

/**
 * Class CommitmentOption
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int                    $id
 * @property string                 $name
 * @property int                    $order
 * @property bool                   $is_custom_input
 * @property string                 $description
 * @property CommitmentItem         $item
 * @property array|QuestionOption[] $questionOptions
 */
class CommitmentOption extends ActiveRecord {

	use WithItemTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{commitment_options}}';
	}


	/**
	 * @return \yii\db\ActiveQuery
	 * @throws \yii\base\InvalidConfigException
	 */
	public function getQuestionOptions() {
		return $this->hasMany(QuestionOption::class, ['id' => 'commitment_option_id'])
			->viaTable('commitments_by_questions', ['question_option_id' => 'id']);
	}


	/**
	 * @param Request               $request
	 *
	 * @param UserQuestionFill      $questionFill
	 * @param int                   $instanceNumber
	 * @param QuestionInstance|null $instance
	 *
	 * @return bool
	 */
	public function isChecked(Request $request, UserQuestionFill $questionFill, int $instanceNumber, ?QuestionInstance $instance = null): bool {
		$checked = false;
		if ($this->item && $this->item->isOnlyCustomInput()) {
			$checked = true;
		}
		if ($request->isPost &&
			$this->item &&
			!empty($request->getBodyParam('options')) &&
			!empty($request->getBodyParam('options')[$this->item->id][$this->id][$instanceNumber])
		) {
			$checked = true;
		} elseif (!empty($this->questionOptions)) {
			foreach ($this->questionOptions as $questionOption) {
				$questionFill;
			}
			return $checked;
		}
		return $checked;
	}


}
