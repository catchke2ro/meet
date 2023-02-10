<?php

namespace meetbase\models;

use meetbase\models\interfaces\ItemInterface;
use meetbase\models\traits\WithCategoryTrait;
use meetbase\models\traits\WithOptionsTrait;
use Yii;
use yii\db\ActiveRecord;
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
 * @property bool                     $is_active
 * @property string                   $description
 * @property array|CommitmentOption[] $options
 * @property CommitmentCategory       $category
 * @property int                      $commitment_category_id
 * @property int                      $month_step
 * @property int                      $months_min
 * @property int                      $months_max
 */
abstract class CommitmentItem extends ActiveRecord implements ItemInterface {

	use WithOptionsTrait;
	use WithCategoryTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'] . 'commitments';
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
	 * @return bool
	 */
	public function getIntervalValue(Request $request, int $instance) {
		$value = $this->months_min;
		if ($request->isPost &&
		    !empty($request->getBodyParam('intervals')) &&
		    !empty($request->getBodyParam('options')[$this->id][$instance])
		) {
			$value = (int) $request->getBodyParam('options')[$this->id][$instance];
		}

		return $value;
	}


}