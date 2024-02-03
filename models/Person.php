<?php

namespace app\models;

use app\lib\enums\PersonType;
use DateTime;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * Class Person
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int            $id
 * @property int            $name
 * @property string         $type
 * @property string         $typeLabel
 * @property string         $isActive
 * @property DateTime       $created_at
 * @property DateTime       $updated_at
 * @property User           $user
 * @property Email[]        $emails
 * @property ?Email         $email
 * @property Organization[] $organizations
 */
class Person extends BaseModel {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'people';
	}


	/**
	 * @return ActiveQuery
	 * @throws InvalidConfigException
	 */
	public function getEmails(): ActiveQuery {
		return $this->hasMany(Email::class, ['id' => 'email_id'])->viaTable('person_emails', ['person_id' => 'id']);
	}


	/**
	 * @return ActiveQuery
	 * @throws InvalidConfigException
	 */
	public function getOrganizations(): ActiveQuery {
		return $this->hasMany(Organization::class, ['id' => 'organization_id'])->viaTable('organization_people', ['person_id' => 'id']);
	}


	/**
	 * @return Email|null
	 * @throws InvalidConfigException
	 */
	public function getEmail(): ?Email {
		return $this->emails[0] ?? null;
	}


	/**
	 * @return ActiveQuery
	 */
	public function getUser(): ActiveQuery {
		return $this->hasOne(User::class, ['person_id' => 'id']);
	}


	/**
	 * @return Organization|null
	 */
	public function getOrganization(): ?Organization {
		return $this->organizations[0] ?? null;
	}


	/**
	 * @return string|null
	 */
	public function getTypeLabel(): ?string {
		return PersonType::tryFrom($this->type)?->getLabel();
	}


}
