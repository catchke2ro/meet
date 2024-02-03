<?php

namespace app\models;

/**
 * Class CommitmentCategoryOrgType
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int $orgTypeId
 * @property int $commitmentCategoryId
 */
class CommitmentCategoryOrgType extends BaseModel {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'commitment_category_org_types';
	}


}
