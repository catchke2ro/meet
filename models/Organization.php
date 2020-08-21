<?php

namespace app\models;

use app\components\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Organization
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int    $id
 * @property string $name
 * @property string $address
 * @property string $email
 * @property string $phone
 * @property string $company_number
 * @property string $tax_number
 * @property int    $remote_id
 * @property string $created_at
 * @property string $updated_at
 */
class Organization extends ActiveRecord {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{meet_organizations}}';
	}


	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			TimestampBehavior::class,
		];
	}


	/**
	 * @return array
	 */
	public function rules() {
		return [];
	}


}
