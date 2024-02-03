<?php

namespace app\modules\admin\models\forms;

use app\lib\enums\PersonType;
use app\models\Address;
use app\models\Email;
use app\models\Organization;
use app\models\Person;
use app\models\Phone;
use Exception;
use Yii;

/**
 * Class OrganizationEdit
 *
 * PostEdit form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class OrganizationEdit extends OrganizationCreate {

	public Organization $organization;


	/**
	 * @param Organization $organization
	 */
	public function loadOrganization(Organization $organization): void {
		$this->organization = $organization;
		$this->orgTypeId = $organization->organizationTypeId;
		$this->orgName = $organization->name;
		$this->orgAddressZip = $organization->address?->zip;
		$this->orgAddressCity = $organization->address?->city;
		$this->orgAddressStreet = $organization->address?->address;
		$this->orgPhone = $organization->phone?->number;
		$this->orgEmail = $organization->email?->email;
		$this->refereeName = $organization->meetReferee?->name;
		$this->refereeEmail = $organization->meetReferee?->email?->email;
		$this->pastorName = $organization->pastor?->name;
		$this->pastorEmail = $organization->pastor?->email?->email;
		$this->superintendentName = $organization->superintendent?->name;
	}


	/**
	 * Signs post up.
	 *
	 * @return Organization|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function edit(): ?Organization {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$this->organization->name = $this->orgName;
			$this->organization->organizationTypeId = $this->orgTypeId;
			$success &= $this->organization->save();

			$newAddress = false;
			$address = $this->organization->address;
			if (!$address) {
				$newAddress = true;
				$address = new Address;
			}
			$address->zip = $this->orgAddressZip;
			$address->city = $this->orgAddressCity;
			$address->address = $this->orgAddressStreet;
			$success &= $address->save();
			if ($newAddress) {
				$this->organization->link('addresses', $address);
			}

			$newPhone = false;
			$phone = $this->organization->phone;
			if (!$phone) {
				$newPhone = true;
				$phone = new Phone();
			}
			$phone->number = $this->orgPhone;
			$success &= $phone->save();
			if ($newPhone) {
				$this->organization->link('phones', $phone);
			}

			$newEmail = false;
			$email = $this->organization->email;
			if (!$email) {
				$newEmail = true;
				$email = new Email();
			}
			$email->email = $this->orgEmail;
			$success &= $email->save();
			if ($newEmail) {
				$this->organization->link('emails', $email);
			}

			$newMeetReferee = false;
			$meetReferee = $this->organization->meetReferee;
			if (!$meetReferee) {
				$newMeetReferee = true;
				$meetReferee = new Person();
			}
			$meetReferee->type = PersonType::MeetReferee;
			$meetReferee->name = $this->refereeName;
			$meetReferee->isActive = true;
			$success &= $meetReferee->save();
			if ($newMeetReferee) {
				$this->organization->link('people', $meetReferee);
			}

			$newMeetRefereeEmail = false;
			$meetRefereeEmail = $meetReferee->email;
			if (!$meetRefereeEmail) {
				$newMeetRefereeEmail = true;
				$meetRefereeEmail = new Email();
			}
			$meetRefereeEmail->email = $this->refereeEmail;
			$success &= $meetRefereeEmail->save();
			if ($newMeetRefereeEmail) {
				$meetReferee->link('emails', $meetRefereeEmail);
			}

			$newPastor = false;
			$pastor = $this->organization->pastor;
			if (!$pastor) {
				$newPastor = true;
				$pastor = new Person();
			}
			$pastor->type = PersonType::Pastor;
			$pastor->name = $this->pastorName;
			$pastor->isActive = true;
			$success &= $pastor->save();
			if ($newPastor) {
				$this->organization->link('people', $pastor);
			}

			$newPastorEmail = false;
			$pastorEmail = $pastor->email;
			if (!$pastorEmail) {
				$newPastorEmail = true;
				$pastorEmail = new Email();
			}
			$pastorEmail->email = $this->pastorEmail;
			$success &= $pastorEmail->save();
			if ($newPastorEmail) {
				$pastor->link('emails', $pastorEmail);
			}

			$newSuperintendent = false;
			$superintendent = $this->organization->superintendent;
			if (!$superintendent) {
				$newSuperintendent = true;
				$superintendent = new Person();
			}
			$superintendent->type = PersonType::Superintendent;
			$superintendent->name = $this->superintendentName;
			$superintendent->isActive = true;
			$success &= $superintendent->save();
			if ($newSuperintendent) {
				$this->organization->link('people', $superintendent);
			}

			$transaction->commit();

			return $success ? $this->organization : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}
