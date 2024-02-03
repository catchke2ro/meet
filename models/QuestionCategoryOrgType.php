<?php

namespace app\models;

/**
 * Class QuestionCategoryOrgType
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int $orgTypeId
 * @property int $questionCategoryId
 */
class QuestionCategoryOrgType extends BaseModel {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'question_category_org_types';
	}


}
