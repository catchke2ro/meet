<?php

namespace app\models;

use app\models\interfaces\DataTableModelInterface;
use app\models\traits\WithItemTrait;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\web\Request;

/**
 * Class QuestionOption
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                $id
 * @property string             $name
 * @property int                $order
 * @property bool               $isCustomInput
 * @property string             $description
 * @property QuestionItem       $item
 * @property int                $questionId
 * @property CommitmentOption[] $commitmentOptions
 */
class QuestionOption extends BaseModel implements DataTableModelInterface {

	use WithItemTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'question_options';
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
	 * @return ActiveQuery
	 * @throws InvalidConfigException
	 */
	public function getCommitmentOptions(): ActiveQuery {
		return $this->hasMany(CommitmentOption::class, ['id' => 'commitment_option_id'])
			->viaTable('commitments_by_questions', ['question_option_id' => 'id']);
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
			'isCustomInput' => $this->isCustomInput
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'edit'   => '<a href="/admin/question-options/edit/' . $this->id . '" class="fa fa-pencil" title="Szereksztés"></a>',
			'delete' => '<a href="/admin/question-options/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
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
