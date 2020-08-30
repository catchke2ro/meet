<?php

namespace meetbase\models;

use meetbase\models\interfaces\ItemInterface;
use meetbase\models\traits\WithCategoryTrait;
use meetbase\models\traits\WithOptionsTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class QuestionItem
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                    $id
 * @property string                 $name
 * @property int                    $order
 * @property string                 $description
 * @property array|QuestionOption[] $options
 * @property QuestionCategory       $category
 * @property int                    $question_category_id
 */
abstract class QuestionItem extends ActiveRecord implements ItemInterface {

	use WithOptionsTrait;
	use WithCategoryTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'].'questions';
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
