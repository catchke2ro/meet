<?php

namespace meetbase\models\lutheran;

use meetbase\models\traits\SharedModelTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Organization
 *
 * @property int              $id
 * @property int              $ref_regi_id
 * @property int              $ref_kategoria_id
 * @property int              $ref_tipus_id
 * @property string           $nev
 * @property int              $erv_allapot
 * @property int              $kerulet_gen
 * @property OrganizationType $orgType
 * @property array|Event[]    $events
 * @property Event            $registrationEvent
 * @property Event            $positionEvent
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


}
