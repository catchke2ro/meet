<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class QuestionOption
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionOption extends ActiveRecord {



	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{question_options}}';
	}


}
