<?php

namespace meetbase\models;

use meetbase\models\traits\SharedModelTrait;
use meetbase\models\traits\WithItemTrait;
use Yii;
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
 * @property int                    $score
 * @property CommitmentItem         $item
 * @property int                    $commitment_id
 * @property array|QuestionOption[] $questionOptions
 */
abstract class CommitmentOption extends ActiveRecord {

	use WithItemTrait;
	use SharedModelTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'].'commitment_options';
	}


	/**
	 * @return \yii\db\ActiveQuery
	 * @throws \yii\base\InvalidConfigException
	 */
	public function getQuestionOptions() {
		return $this->hasMany($this->getModelClass(QuestionOption::class), ['id' => 'commitment_option_id'])
			->viaTable(Yii::$app->params['table_prefix'].'commitments_by_questions', ['question_option_id' => 'id']);
	}


	/**
	 * @param Request               $request
	 *
	 * @param OrgQuestionFill      $questionFill
	 * @param int                   $instanceNumber
	 * @param QuestionInstance|null $instance
	 *
	 * @return bool
	 */
	public function isChecked(Request $request, OrgQuestionFill $questionFill, int $instanceNumber, ?QuestionInstance $instance = null): bool {
		$checked = false;
		if ($this->item && $this->item->isOnlyCustomInput()) {
			$checked = true;
		}
		if ($request->isPost &&
			$this->item &&
			!empty($request->getBodyParam('options')) &&
			!empty($request->getBodyParam('options')[$this->item->id][$instanceNumber]) &&
			$request->getBodyParam('options')[$this->item->id][$instance] == $this->id
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
