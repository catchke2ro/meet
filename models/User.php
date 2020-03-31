<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class User
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int    $id
 * @property string $username
 * @property string $password
 */
class User extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{users}}';
	}


}
