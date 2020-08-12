<?php

namespace app\models;

use app\models\interfaces\DataTableModelInterface;
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
 * @property int                    $score
 * @property CommitmentItem         $item
 * @property int                    $commitment_id
 * @property array|QuestionOption[] $questionOptions
 */
class CommitmentOption extends ActiveRecord implements DataTableModelInterface {

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


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {

		return [
			'id'            => $this->id,
			'name'          => $this->name,
			'description'   => $this->description,
			'order'         => $this->order,
			'isCustomInput' => $this->is_custom_input,
			'score'         => $this->score
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'edit'   => '<a href="/admin/commitment-options/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>',
			'delete' => '<a href="/admin/commitment-options/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getTextSearchColumns(): array {
		return [
			'name',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getOrderableColumns(): array {
		return [
			'name',
			'order'
		];
	}


}
