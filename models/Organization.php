<?php

namespace app\models;

use app\lib\enums\PersonType;
use app\models\interfaces\DataTableModelInterface;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Organization
 *
 * @property int                 $id
 * @property string              $name
 * @property bool                $isActive
 * @property int                 $organizationTypeId
 * @property string              $authorizationFilename
 * @property OrganizationType    $organizationType
 * @property OrgCommitmentFill[] $commitmentFills
 * @property Phone[]             $contacts
 * @property Email[]             $phoneContacts
 * @property Address[]           $emailContacts
 * @property Person[]            $people
 * @property Phone               $phone
 * @property Address             $address
 * @property Email               $email
 * @property Person              $meetReferee
 * @property Person              $pastor
 * @property Person              $superintendent
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Organization extends BaseModel implements DataTableModelInterface {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'organizations';
	}


	/**
	 * Get phones
	 */
	public function getOrganizationType(): ActiveQuery {
		return $this->hasOne(OrganizationType::class, ['id' => 'organization_type_id']);
	}


	/**
	 * Get phones
	 */
	public function getPhones(): ActiveQuery {
		return $this->hasMany(Phone::class, ['id' => 'phone_id'])->viaTable('organization_phones', ['organization_id' => 'id']);
	}


	/**
	 * Get emails
	 */
	public function getEmails(): ActiveQuery {
		return $this->hasMany(Email::class, ['id' => 'email_id'])->viaTable('organization_emails', ['organization_id' => 'id']);
	}


	/**
	 * Get org type
	 */
	public function getAddresses(): ActiveQuery {
		return $this->hasMany(Address::class, ['id' => 'address_id'])->viaTable('organization_addresses', ['organization_id' => 'id']);
	}


	/**
	 * Get org type
	 */
	public function getPeople(): ActiveQuery {
		return $this->hasMany(Person::class, ['id' => 'person_id'])->viaTable('organization_people', ['organization_id' => 'id']);
	}


	/**
	 * @return Address|null
	 */
	public function getAddress(): ?Address {
		return $this->getAddresses()->one();
	}


	/**
	 * @return Phone|null
	 */
	public function getPhone(): ?Phone {
		return $this->getPhones()->one();
	}


	/**
	 * @return Email|null
	 */
	public function getEmail(): ?Email {
		return $this->getEmails()->one();
	}


	/**
	 * @return ActiveQuery
	 */
	public function getCommitmentFills(): ActiveQuery {
		return $this->hasMany(OrgCommitmentFill::class, ['org_id' => 'id']);
	}


	/**
	 * @param bool $approvedOnly
	 *
	 * @return bool
	 */
	public function hasCommitmentFill(bool $approvedOnly = false): bool {
		$fills = $this->commitmentFills ?: [];
		if ($approvedOnly) {
			$fills = array_filter($fills, function (OrgCommitmentFill $fill) {
				return $fill->isApproved;
			});
		}

		return count($fills) > 0;
	}


	/**
	 * @return OrgCommitmentFill|null
	 */
	public function getLatestCommitmentFill(): ?OrgCommitmentFill {
		return $this->getCommitmentFills()->orderBy('date DESC')->limit(1)->one();
	}


	/**
	 * @return OrgCommitmentFill|null
	 */
	public function getLatestApprovedCommitmentFill(): ?OrgCommitmentFill {
		return $this->getCommitmentFills()->orderBy('date DESC')->andWhere(['approved' => 1])->limit(1)->one();
	}


	/**
	 * @return Module|null
	 */
	public function getLatestApprovedModule(): ?Module {
		if (($fill = $this->getLatestApprovedCommitmentFill())) {
			return $fill->getFinalModule();
		}

		return null;
	}


	/**
	 * @param PersonType $type
	 *
	 * @return Person|null
	 */
	public function getPersonWithType(PersonType $type): ?Person {
		return array_values(array_filter($this->people, fn(Person $person) => $person->type == $type->value))[0] ?? null;
	}


	/**
	 * @return Person|null
	 */
	public function getMeetReferee(): ?Person {
		return $this->getPersonWithType(PersonType::MeetReferee);
	}


	/**
	 * @return Person|null
	 */
	public function getSuperintendent(): ?Person {
		return $this->getPersonWithType(PersonType::Superintendent);
	}


	/**
	 * @return Person|null
	 */
	public function getPastor(): ?Person {
		return $this->getPersonWithType(PersonType::Pastor);
	}


	/**
	 * @return Person|null
	 */
	public function getPastorGeneral(): ?Person {
		return $this->getPersonWithType(PersonType::PastorGeneral);
	}


	/**
	 * @param string|null $search
	 *
	 * @param array|null  $orgTypes
	 * @param bool        $mapKeys
	 *
	 * @return array|ActiveRecord[]
	 */
	public static function getList(?string $search = null, array $orgTypes = null, bool $mapKeys = true): array {
		$qb = self::find();
		$qb->select(['id', 'name']);
		if ($search) {
			$qb->andWhere(['like', 'name', $search]);
		}
		if (!empty($orgTypes)) {
			$qb->andWhere(['organization_type_id' => $orgTypes]);
		}
		if ($mapKeys) {
			$organizations = [];
			/** @var Organization $org */
			foreach ($qb->all() as $org) {
				$organizations[$org->id] = $org->name;
			}

			return $organizations;
		} else {
			return $qb->all();
		}
	}


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {
		return [
			'id'   => $this->id,
			'name' => $this->name,
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'edit' => '<a href="/admin/organizations/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getTextSearchColumns(): array {
		return [
			'name',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getOrderableColumns(): array {
		return [
			'id',
			'name',
		];
	}


}
