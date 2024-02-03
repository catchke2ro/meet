<?php

namespace app\models;

use DateTime;
use yii\db\ActiveQuery;

/**
 * Class Contact
 *
 * @property int          $id
 * @property string       $email
 * @property string       $name
 * @property string       $message
 * @property int          $organizationId
 * @property Organization $organization
 * @property DateTime     $date
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Contact extends BaseModel {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'contacts';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrganizations() {
		return $this->hasOne(Organization::class, ['id' => 'organization_id']);
	}


}
