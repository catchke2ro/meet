<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class QuestionCategory
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionCategory extends ActiveRecord {



	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{question_categories}}';
	}


}
