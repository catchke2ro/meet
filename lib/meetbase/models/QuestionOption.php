<?php

namespace meetbase\models;

use meetbase\models\traits\WithItemTrait;
use PDO;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Request;

/**
 * Class QuestionOption
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int          $id
 * @property string       $name
 * @property int          $order
 * @property bool         $is_custom_input
 * @property string       $description
 * @property QuestionItem $item
 * @property int          $question_id
 */
abstract class QuestionOption extends ActiveRecord {

	use WithItemTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'].'question_options';
	}


	/**
	 * @param Request $request
	 *
	 * @param int     $instance
	 *
	 * @return bool
	 */
	public function isChecked(Request $request, int $instance): bool {
		$checked = false;
		if ($this->item && $this->item->isOnlyCustomInput()) {
			$checked = true;
		}
		if ($request->isPost &&
			$this->item &&
			!empty($request->getBodyParam('options')) &&
			!empty($request->getBodyParam('options')[$this->item->id][$instance]) &&
			$request->getBodyParam('options')[$this->item->id][$instance] == $this->id
		) {
			$checked = true;
		}

		return $checked;
	}


	/**
	 *
	 */
	public function getCommitmentOptions() {
		return Yii::$app->db
			->createCommand("SELECT `commitment_option_id` FROM `".Yii::$app->params['table_prefix']."commitments_by_questions` WHERE `question_option_id` = {$this->id}")
			->queryAll(PDO::FETCH_COLUMN) ?: [];
	}

}
