<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Organization
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int    $id
 * @property string $name
 * @property int $threshold
 */
class Badge extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{badges}}';
	}


	/**
	 * @return array
	 */
	public function rules() {
		return [];
	}


}
