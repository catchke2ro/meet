<?php

namespace meetbase\models\lutheran;

use meetbase\models\OrgCommitmentFill;
use meetbase\models\traits\SharedModelTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Organization
 *
 * @property int                 $id
 * @property int                 $ref_regi_id
 * @property int                 $ref_kategoria_id
 * @property int                 $ref_tipus_id
 * @property string              $nev
 * @property int                 $erv_allapot
 * @property int                 $kerulet_gen
 * @property OrganizationType    $orgType
 * @property array|Event[]       $events
 * @property Event               $registrationEvent
 * @property Event               $positionEvent
 * @property OrgCommitmentFill[] $commitmentFills
 * @property Contact[]           $contacts
 * @property Contact[]           $phoneContacts
 * @property Contact[]           $emailContacts
 * @property Contact[]           $addressContacts
 * @property Contact[]           $gpsContacts
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
abstract class Organization extends ActiveRecord {

	use SharedModelTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'szervegyseg__t__szervegyseg';
	}


	/**
	 * Get org type
	 */
	public function getOrgType() {
		return $this->hasOne($this->getModelClass(OrganizationType::class), ['id' => 'ref_tipus_id']);
	}


	/**
	 * Get org type
	 */
	public function getEvents() {
		return $this->hasMany($this->getModelClass(Event::class), ['ref_szervegyseg_id' => 'id']);
	}


	/**
	 * Get org type
	 */
	public function getContacts() {
		return $this->hasMany($this->getModelClass(Contact::class), ['ref_szervegyseg_id' => 'id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getCommitmentFills() {
		return $this->hasMany($this->getModelClass(OrgCommitmentFill::class), ['org_id' => 'id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getRegistrationEvent() {
		$qb = clone $this->getEvents();
		$qb->multiple = false;
		$qb->andOnCondition([
			'registrationEvent.ref_tipus_id' => Yii::$app->params['event_type_meet_reg']
		]);

		return $qb;
	}


	/**
	 * @return ActiveQuery
	 */
	public function getPositionEvent() {
		$qb = clone $this->getEvents();
		$qb->andOnCondition([
			'positionEvent.ref_tipus_id' => Yii::$app->params['event_type_pozicio'],
			'positionEvent.ref2_id'      => Yii::$app->params['position_meet_referer']
		]);
		$qb->multiple = false;

		return $qb;
	}


	/**
	 * @return ActiveQuery
	 */
	public function getPhoneContacts() {
		$qb = clone $this->getContacts();
		$qb->andOnCondition([
			'phoneContact.ref_tipus_id' => ContactType::ID_PHONE
		]);
		return $qb;
	}


	/**
	 * @return ActiveQuery
	 */
	public function getEmailContacts() {
		$qb = clone $this->getContacts();
		$qb->alias('emailContact');
		$qb->andOnCondition([
			'emailContact.ref_tipus_id' => ContactType::ID_EMAIL
		]);
		return $qb;
	}


	/**
	 * @return ActiveQuery
	 */
	public function getAddressContacts() {
		$qb = clone $this->getContacts();
		$qb->alias('addressContact');
		$qb->andOnCondition([
			'addressContact.ref_tipus_id' => ContactType::ID_ADDRESS
		]);
		return $qb;
	}


	/**
	 * @return ActiveQuery
	 */
	public function getGpsContacts() {
		$qb = clone $this->getContacts();
		$qb->andOnCondition([
			'gpsContact.ref_tipus_id' => ContactType::ID_GPS
		]);
		return $qb;
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
				return $fill->isApproved();
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
	 * @param int $role
	 *
	 * @return array|ActiveRecord|null
	 */
	public function getPersonWithRole(int $role) {
		return ($this->getModelClass(Person::class))::find()
			->alias('person')
			->innerJoin(Event::tableName().' as event', 'event.ref_szemely_id = person.id')
			->innerJoin(Organization::tableName().' as organization', 'event.ref_szervegyseg_id = organization.id')
			->andWhere(['event.ref_tipus_id' => Yii::$app->params['event_type_pozicio']])
			->andWhere(['event.ref2_id' => $role])
			->andWhere(['organization.id' => $this->id])
			->one();
	}


	/**
	 * @return array|ActiveRecord|null
	 */
	public function getMeetReferer(): ?Person {
		return $this->getPersonWithRole(Yii::$app->params['position_meet_referer']);
	}


	/**
	 * @return array|ActiveRecord|null
	 */
	public function getSuperintendent(): ?Person {
		return $this->getPersonWithRole(Yii::$app->params['position_superintendent']);
	}


	/**
	 * @return array|ActiveRecord|null
	 */
	public function getPastor(): ?Person {
		return $this->getPersonWithRole(Yii::$app->params['position_pastor']);
	}


	/**
	 * @return array|ActiveRecord|null
	 */
	public function getPastorGeneral(): ?Person {
		return $this->getPersonWithRole(Yii::$app->params['position_pastor_general']);
	}


}
