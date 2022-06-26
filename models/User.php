<?php

namespace app\models;

use app\components\Email;
use app\components\Pdf;
use app\models\lutheran\Person;
use app\modules\meet\models\interfaces\DataTableModelInterface;
use Exception;
use meetbase\models\lutheran\Event;
use meetbase\models\lutheran\Organization;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 *
 * @property int               id
 * @property string            username
 * @property string            password
 * @property array             email
 * @property string            registered_at
 * @property string            logged_in_at
 * @property bool              is_active
 * @property bool              is_admin
 * @property bool              is_approved_admin
 * @property bool              is_approved_boss
 * @property int               person_id
 * @property Person|null       person
 * @property Organization|null $organization
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class User extends ActiveRecord implements DataTableModelInterface, IdentityInterface {

	/**
	 * @var AdminOrganization|false|mixed
	 */
	private $organizationCache;


	public function init() {
		parent::init();
		$this->on(self::EVENT_AFTER_UPDATE, [$this, 'sendEmails']);
	}


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'] . 'users';
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'edit' => '<a href="/meet/users/edit?id=' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>'
		];
	}


	/**
	 * @param $id
	 *
	 * @return User|IdentityInterface|null
	 */
	public static function findIdentity($id) {
		return self::findOne(['id' => $id]);
	}


	/**
	 * @param $token
	 * @param $type
	 *
	 * @return IdentityInterface|null
	 * @throws NotSupportedException
	 */
	public static function findIdentityByAccessToken($token, $type = null) {
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented . ');
	}


	/**
	 * @return int|string
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @return null
	 */
	public function getAuthKey() {
		return null;
	}


	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 *
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password) {
		return password_verify($password, $this->password);
	}


	/**
	 * Validates password
	 *
	 * @return bool if password provided is valid for current user
	 */
	public function validateRegistration() {
		$organization = $this->getOrganization();
		if (!$organization) {
			return 0;
		}
		$approvedEvent = $this->getActiveMeetApprovedEvent($organization);
		if (!$approvedEvent) {
			return 1;
		}

		return 2;
	}


	/**
	 * @param $authKey
	 *
	 * @return null
	 */
	public function validateAuthKey($authKey) {
		return null;
	}


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {
		return [
			'id'        => $this->id,
			'username'  => $this->username,
			'email'     => $this->email,
			'name'      => $this->person ? $this->person->nev : null,
			'is_active' => $this->is_active ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>',
			'is_admin'  => $this->is_admin ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>'
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getTextSearchColumns(): array {
		return [
			'email',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getOrderableColumns(): array {
		return [
			'id',
			'email',
			'is_approved_admin',
			'is_approved_boss'
		];
	}


	/**
	 * @return Person|null
	 */
	public function getPerson() {
		return $this->hasOne(Person::class, ['id' => 'person_id']);
	}


	/**
	 * @return mixed|null
	 */
	public function getEvents(): ?ActiveQuery {
		return $this->person ? $this->person->getEvents() : null;
	}


	/**
	 * @param bool $forceReload
	 *
	 * @return Organization|null
	 */
	public function getOrganization(bool $forceReload = false): ?Organization {
		if (is_null($this->organizationCache) || $forceReload) {
			if (!empty(($organizations = $this->getOrganizationsByPositionEvents()))) {
				$this->organizationCache = reset($organizations);
			}
			if (is_null($this->organizationCache) && $this->isAdmin()) {
				$this->organizationCache = new AdminOrganization();
			}
		}

		return $this->organizationCache;
	}


	/**
	 * @return Organization|null
	 */
	public function getAnyActiveOrganization(): ?Organization {
		if (!empty(($organizations = $this->getOrganizationsByPositionEvents(true)))) {
			return reset($organizations);
		}

		return null;
	}


	/**
	 * @param bool $allowInactive
	 *
	 * @return array
	 */
	public function getOrganizationsByPositionEvents(bool $allowInactive = false): array {
		$events = $this->getActivePositionEvents($allowInactive);

		return array_map(function (Event $event) {
			return $event->organization;
		}, $events);
	}


	/**
	 * @param bool $allowInactive
	 *
	 * @return array
	 */
	public function getActivePositionEvents(bool $allowInactive = false): array {
		if (!$this->person) {
			return [];
		}
		$conditions = [
			'ref_tipus_id' => Yii::$app->params['event_type_pozicio'],
			'ref2_id'      => Yii::$app->params['position_meet_referer'],
		];
		if (!$allowInactive) {
			$conditions['erv_allapot'] = Yii::$app->params['org_position_valid_erv_allapot'];
		}
		$qb = $this->getEvents()->andOnCondition($conditions);

		return $qb->all() ?: [];
	}


	/**
	 * @param Organization $organization
	 *
	 * @return Event|null
	 */
	public function getActiveMeetApprovedEvent(Organization $organization): ?Event {
		if (!$this->person) {
			return null;
		}
		$qb = $this->getEvents()
			->andOnCondition([
				'ref_tipus_id'       => Yii::$app->params['event_type_meet_reg_approved'],
				'erv_allapot'        => Yii::$app->params['org_meet_reg_valid_erv_allapot'],
				'ref_szervegyseg_id' => $organization->id,
				'ertek1'             => 1
			]);

		return $qb->one();
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->person ? $this->person->nev : $this->username;
	}


	/**
	 * @return bool
	 */
	public function isAdmin(): bool {
		return $this->is_admin;
	}


	/**
	 * @return mixed|null
	 */
	public function getOrgTypeId(): ?int {
		if (($organization = $this->getOrganization())) {
			return $organization->orgType?->id;
		}

		return null;
	}


	/**
	 * @param \yii\base\Event $event
	 *
	 * @return void
	 * @throws Exception
	 */
	public function sendEmails(\yii\base\Event $event): void {
		$changedApprovedAdmin = isset($event->changedAttributes) &&
			isset($event->changedAttributes['is_approved_admin']) &&
			$event->changedAttributes['is_approved_admin'] == 0 &&
			$this->is_approved_admin == 1;
		if ($changedApprovedAdmin) {
			try {
				/** @var \app\models\lutheran\Event $positionEvent */
				foreach ($this->getActivePositionEvents(true) as $positionEvent) {
					$positionEvent->erv_allapot = 1;
					$positionEvent->save();
				}

				$pdfFilename = (new Pdf())->generatePdf('@app/views/pdf/mustar', 'Mustarmag.pdf', [
					'organization' => $this->organization
				]);

				(new Email())->sendEmail(
					'approved_registration',
					$this->person->getEmail(),
					'MEET Értesítő sikeres regisztrációról',
					['person' => $this->person, 'organization' => $this->organization],
					[$pdfFilename]
				);

				/*if (!empty($notMailedApprovedReg->organization->emailContacts) && (Yii::$app->params['email_to_org'] ?? false)) {
					$superintendent = $notMailedApprovedReg->organization->getSuperintendent();
					$pastor = $notMailedApprovedReg->organization->getPastorGeneral() ?: $notMailedApprovedReg->organization->getPastor();
					$meetReferer = $notMailedApprovedReg->organization->getMeetReferer();

					$emailContact = reset($notMailedApprovedReg->organization->emailContacts);
					(new Email())->sendEmail(
						'approved_registration_org',
						$emailContact->ertek1,
						'MEET program tagsági felvétel értesítő',
						[
							'person'         => $notMailedApprovedReg->person,
							'organization'   => $notMailedApprovedReg->organization,
							'superintendent' => $superintendent,
							'pastor'         => $pastor,
							'meetReferer'    => $meetReferer,
						]
					);
				}*/

				$regEvents = \app\models\lutheran\Event::find()
					->andWhere(['ref_tipus_id' => Yii::$app->params['event_type_meet_reg_approved']])
					->andWhere(['ref_szervegyseg_id' => $this->organization->id])
					->all();
				if (empty($regEvents)) {
					$regEvent = \app\models\lutheran\Event::createRegApprovedEvent($this->organization, $this->person);
					$regEvent->save();
				} else {
					$regEvent = reset($regEvents);
				}
				$regEvent->ertek1 = 1;
				$regEvent->ertek2 = 1;
				$regEvent->save();
			} catch (Exception $e) {
				throw $e;
				//Continue to next event on error
			}

			$this->is_active = 1;
			$this->save();
		}
	}


}
