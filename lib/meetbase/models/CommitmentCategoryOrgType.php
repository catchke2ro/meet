<?php

namespace meetbase\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class CommitmentCategoryOrgType
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int $org_type_id
 * @property int $commitment_category_id
 */
abstract class CommitmentCategoryOrgType extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'].'commitment_category_org_types';
	}


}
