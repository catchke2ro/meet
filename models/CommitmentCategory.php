<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class CommitmentCategory
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentCategory extends ActiveRecord {



	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{commitment_categories}}';
	}


}
