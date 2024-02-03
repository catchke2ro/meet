<?php

namespace app\models;

use DateTime;

/**
 * Class Contact
 *
 * @property int      $id
 * @property string   $email
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Email extends BaseModel {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'emails';
	}


}
