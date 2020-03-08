<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Question
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Question extends ActiveRecord {



	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{questions}}';
	}


}
