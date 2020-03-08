<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Commitment
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Commitment extends ActiveRecord {



	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{commitments}}';
	}


}
