<?php

namespace app\models;

use app\models\interfaces\DataTableModelInterface;
use app\models\interfaces\ItemInterface;
use app\models\traits\WithCategoryTrait;
use app\models\traits\WithOptionsTrait;
use yii\web\Request;

/**
 * Class CommitmentItem
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                      $id
 * @property string                   $name
 * @property int                      $order
 * @property bool                     $isActive
 * @property string                   $description
 * @property array|CommitmentOption[] $options
 * @property CommitmentCategory       $category
 * @property int                      $commitmentCategoryId
 * @property int                      $monthStep
 * @property int                      $monthsMin
 * @property int                      $monthsMax
 */
class CommitmentItem extends BaseModel implements ItemInterface, DataTableModelInterface {

	use WithOptionsTrait;
	use WithCategoryTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'commitments';
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
	 * @param Request $request
	 *
	 * @param int     $instance
	 *
	 * @return int
	 */
	public function getIntervalValue(Request $request, int $instance): int {
		$value = $this->monthsMin;
		if ($request->isPost &&
			!empty($request->getBodyParam('intervals')) &&
			!empty($request->getBodyParam('options')[$this->id][$instance])
		) {
			$value = (int) $request->getBodyParam('options')[$this->id][$instance];
		}

		return $value;
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
			'items'  => '<a href="/admin/commitment-options?itemId=' . $this->id . '" class="fa fa-list" title="Opciók"></a>',
			'edit'   => '<a href="/admin/commitment-items/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>',
			'delete' => '<a href="/admin/commitment-items/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
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
