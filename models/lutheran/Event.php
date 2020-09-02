<?php

namespace app\models\lutheran;

use app\models\OrgCommitmentFill;
use DateTime;
use meetbase\models\lutheran\Event as BaseEvent;
use Yii;

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
		$event->ref_tipus_id = Yii::$app->params['event_type_pozicio'];
		$event->ref_szervegyseg_id = $organization->id;
		$event->ref_szemely_id = $person->id;
		$event->ref1_id = Yii::$app->params['position_type_megbizott'];
		$event->ref2_id = Yii::$app->params['position_meet_referer'];
		$event->ertek1 = 0;

		return $event;
	}


	/**
	 * @param Organization $organization
	 * @param Person       $person
	 * @param Contact      $emailContact
	 *
	 * @param string       $fileId
	 *
	 * @return Event
	 */
	public static function createNewRegistrationEvent(Organization $organization, Person $person, Contact $emailContact, string $fileId) {
		$event = new Event();
		$event->erv_kezdet = (new DateTime())->format('Y-m-d');
		$event->erv_allapot = 1;
		$event->ref_tipus_id = Yii::$app->params['event_type_meet_reg'];
		$event->ref_szervegyseg_id = $organization->id;
		$event->ref_szemely_id = $person->id;
		$event->ertek1 = $person->nev;
		$event->ertek2 = $emailContact->ertek1;
		$event->ertek3 = "/_authorization-file?id=$fileId&token=";

		return $event;
	}


	/**
	 * @param Organization      $organization
	 * @param Person            $person
	 * @param OrgCommitmentFill $fill
	 *
	 * @return Event
	 */
	public static function createNewCommitmentEvent(Organization $organization, Person $person, OrgCommitmentFill $fill) {
		$event = new Event();
		$event->erv_kezdet = (new DateTime())->format('Y-m-d');
		$event->erv_allapot = 0;
		$event->ref_tipus_id = Yii::$app->params['event_type_meet_commitment'];
		$event->ref_szervegyseg_id = $organization->id;
		$event->ref_szemely_id = $person->id;
		$event->ref1_id = $fill->id;

		return $event;
	}


	/**
	 * @param int    $orgId
	 * @param string $name
	 * @param string $email
	 * @param string $message
	 *
	 * @return Event
	 */
	public static function createNewMessageEvent(int $orgId, string $name, string $email, string $message) {
		$event = new Event();
		$event->erv_kezdet = (new DateTime())->format('Y-m-d');
		$event->erv_allapot = 0;
		$event->ref_tipus_id = Yii::$app->params['event_type_meet_org_message'];
		$event->ref_szervegyseg_id = $orgId;
		$event->ertek1 = $name;
		$event->ertek2 = $email;
		$event->uzenet = $message;

		return $event;
	}


}
