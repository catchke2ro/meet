<?php

namespace app\models;

use app\models\interfaces\DataTableModelInterface;
use app\models\interfaces\ItemInterface;
use app\models\traits\WithCategoryTrait;
use app\models\traits\WithOptionsTrait;

/**
 * Class QuestionItem
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                    $id
 * @property string                 $name
 * @property int                    $order
 * @property bool                   $isActive
 * @property string                 $description
 * @property array|QuestionOption[] $options
 * @property QuestionCategory       $category
 * @property int                    $questionCategoryId
 */
class QuestionItem extends BaseModel implements ItemInterface, DataTableModelInterface {

	use WithOptionsTrait;
	use WithCategoryTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'questions';
	}


	/**
	 * @return string
	 */
	public function getCssClass(): string {
		$classes = [];
		if ($this->isOnlyCustomInput()) {
			$classes[] = 'onlyCustomInput';
		}

		return implode(' ', $classes);
	}


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {

		return [
			'id'          => $this->id,
			'name'        => $this->name,
			'description' => $this->description,
			'order'       => $this->order
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'items'  => '<a href="/admin/question-options?itemId=' . $this->id . '" class="fa fa-list" title="Opciók"></a>',
			'edit'   => '<a href="/admin/question-items/edit/' . $this->id . '" class="fa fa-pencil" title="Szereksztés"></a>',
			'delete' => '<a href="/admin/question-items/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
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
