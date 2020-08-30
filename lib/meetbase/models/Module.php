<?php

namespace meetbase\models;

use meetbase\models\traits\SharedModelTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class Module
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int    $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $description_pdf
 * @property int    $threshold
 */
abstract class Module extends ActiveRecord {

	use SharedModelTrait;

	/**
	 * @var mixed|null
	 */
	protected static $firstModule;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'].'modules';
	}


	/**
	 * @return Module
	 */
	public static function firstModule(): Module {
		if (is_null(self::$firstModule)) {
			self::$firstModule = static::find()->orderBy('threshold ASC')->one();
		}
		return self::$firstModule;
	}


}
