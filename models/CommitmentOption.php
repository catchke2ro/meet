<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class CommitmentOption
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentOption extends ActiveRecord {



	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{commitment_options}}';
	}


}
