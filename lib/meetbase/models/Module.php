<?php

namespace meetbase\models;

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


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'meet_modules';
	}


}
