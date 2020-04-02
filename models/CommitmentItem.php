<?php

namespace app\models;

use app\models\interfaces\ItemInterface;
use app\models\traits\WithCategoryTrait;
use app\models\traits\WithOptionsTrait;
use yii\db\ActiveRecord;

/**
 * Class CommitmentItem
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                      $id
 * @property string                   $name
 * @property int                      $order
 * @property string                   $description
 * @property array|CommitmentOption[] $options
 * @property CommitmentCategory       $category
 */
class CommitmentItem extends ActiveRecord implements ItemInterface {

	use WithOptionsTrait;
	use WithCategoryTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{commitments}}';
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


}
