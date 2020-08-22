<?php

namespace app\models\lutheran;

use DateTime;
use meetbase\models\lutheran\Event as BaseEvent;

/**
 * Class Event
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Event extends BaseEvent {




	/**
	 * @param Organization $organization
	 * @param Person       $person
	 *
	 * @return Event
	 */
	public static function createNewPositionEvent(Organization $organization, Person $person): Event {
		$event = new Event();
		$event->erv_kezdet = (new DateTime())->format('Y-m-d');
		$event->erv_allapot = 0;
		$event->ref_tipus_id = self::ID_TYPE_POSITION;
		$event->ref_szervegyseg_id = $organization->id;
		$event->ref_szemely_id = $person->id;
		$event->ref1_id = self::ID_POSITION_TYPE_ACCREDITED;
		$event->ref2_id = self::ID_POSITION_MEET_REFERER;
		$event->ertek1 = 0;

		return $event;
	}


	/**
	 * @param Organization $organization
	 * @param Person       $person
	 * @param Contact      $emailContact
	 *
	 * @return Event
	 */
	public static function createNewRegistrationEvent(Organization $organization, Person $person, Contact $emailContact) {
		$event = new Event();
		$event->erv_kezdet = (new DateTime())->format('Y-m-d');
		$event->erv_allapot = 1;
		$event->ref_tipus_id = self::ID_TYPE_MEET_REGISTRATION;
		$event->ref_szervegyseg_id = $organization->id;
		$event->ref_szemely_id = $person->id;
		$event->ertek1 = $person->nev;
		$event->ertek2 = $emailContact->ertek1;

		return $event;
	}


}
