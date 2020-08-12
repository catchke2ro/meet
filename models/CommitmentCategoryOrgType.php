<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class CommitmentCategoryOrgType
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int $org_type_id
 * @property int $commitment_category_id
 */
class CommitmentCategoryOrgType extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{commitment_category_org_types}}';
	}


}
