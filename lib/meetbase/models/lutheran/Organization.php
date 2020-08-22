<?php

namespace meetbase\models\lutheran;

use meetbase\models\OrgCommitmentFill;
use meetbase\models\traits\SharedModelTrait;
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
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
abstract class Organization extends ActiveRecord {

	const ID_TYPE_EGYHAZKOZSEG = 1;
	const ID_TYPE_NEVTAR = 1;

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
			'registrationEvent.ref_tipus_id' => Event::ID_TYPE_MEET_REGISTRATION
		]);

		return $qb;
	}


	/**
	 * @return ActiveQuery
	 */
	public function getPositionEvent() {
		$qb = clone $this->getEvents();
		$qb->andOnCondition([
			'positionEvent.ref_tipus_id' => Event::ID_TYPE_POSITION,
			'positionEvent.ref2_id'      => Event::ID_POSITION_MEET_REFERER
		]);
		$qb->multiple = false;

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
				return $fill->approved;
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


}
