<?php

namespace meetbase\models;

use yii\db\ActiveRecord;

/**
 * Class QuestionCategoryOrgType
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int $org_type_id
 * @property int $question_category_id
 */
abstract class QuestionCategoryOrgType extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'meet_question_category_org_types';
	}


}
