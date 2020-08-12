<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class QuestionCategoryOrgType
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int $org_type_id
 * @property int $question_category_id
 */
class QuestionCategoryOrgType extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{question_category_org_types}}';
	}


}
