<?php

namespace app\models;

use app\models\interfaces\DataTableModelInterface;
use app\models\traits\WithItemTrait;
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
class QuestionOption extends ActiveRecord implements DataTableModelInterface {

	use WithItemTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{meet_question_options}}';
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
	 * @return array
	 */
	public function toDataTableArray(): array {

		return [
			'id'            => $this->id,
			'name'          => $this->name,
			'description'   => $this->description,
			'order'         => $this->order,
			'isCustomInput' => $this->is_custom_input
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
