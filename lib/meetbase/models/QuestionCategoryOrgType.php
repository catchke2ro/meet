<?php

namespace meetbase\models;

use Yii;
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
		return Yii::$app->params['table_prefix'].'question_category_org_types';
	}


}
