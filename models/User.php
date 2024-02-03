<?php

namespace app\models;

use app\components\Email;
use app\components\Pdf;
use app\models\interfaces\DataTableModelInterface;
use Exception;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\web\IdentityInterface;

/**
 * Class User
 *
 * @property int               id
 * @property string            username
 * @property string            password
 * @property string            email
 * @property string            registeredAt
 * @property string            loggedInAt
 * @property bool              isActive
 * @property bool              isAdmin
 * @property bool              isApprovedAdmin
 * @property bool              isApprovedBoss
 * @property string            passwordResetToken
 * @property string            passwordResetExpiresAt
 * @property int               personId
 * @property Person|null       person
 * @property Organization|null $organization
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class User extends BaseModel implements DataTableModelInterface, IdentityInterface {


	/**
	 * @return void
	 */
	public function init(): void {
		parent::init();
		$this->on(self::EVENT_AFTER_UPDATE, [$this, 'sendEmails']);
	}


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'users';
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'edit' => '<a href="/admin/users/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>'
		];
	}


	/**
	 * @param int $id
	 *
	 * @return User|IdentityInterface|null
	 */
	public static function findIdentity($id): User|IdentityInterface|null {
		return self::findOne(['id' => $id]);
	}


	/**
	 * @param string $token
	 * @param mixed  $type
	 *
	 * @return IdentityInterface|null
	 * @throws NotSupportedException
	 */
	public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface {
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}


	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}


	/**
	 * @return null
	 */
	public function getAuthKey(): null {
		return null;
	}


	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 *
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword(string $password): bool {
		return password_verify($password, $this->password);
	}


	/**
	 * Validates password
	 *
	 * @return int if password provided is valid for current user
	 */
	public function validateRegistration(): int {
		$organization = $this->getOrganization();
		if (!$organization) {
			return 0;
		}
		$approvedEvent = $this->isApprovedAdmin;
		if (!$approvedEvent) {
			return 1;
		}

		return 2;
	}


	/**
	 * @param string $authKey
	 *
	 * @return null
	 */
	public function validateAuthKey($authKey): null {
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
			'name'      => $this->person ? $this->person->name : null,
			'is_active' => $this->isActive ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>',
			'is_admin'  => $this->isAdmin ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>'
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
	 * @return ActiveQuery
	 */
	public function getPerson(): ActiveQuery {
		return $this->hasOne(Person::class, ['id' => 'person_id']);
	}


	/**
	 * @return Organization|null
	 */
	public function getOrganization(): ?Organization {
		return $this->person?->getOrganization();
	}


	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->person ? $this->person->name : $this->username;
	}


	/**
	 * @return bool
	 */
	public function isAdmin(): bool {
		return $this->isAdmin;
	}


	/**
	 * @return int|null
	 */
	public function getOrgTypeId(): ?int {
		return $this->getOrganization()?->organizationType?->id;
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
			$this->isApprovedAdmin == 1;
		if ($changedApprovedAdmin) {
			try {
				$pdfFilename = (new Pdf())->generatePdf('@app/views/pdf/mustar', 'Mustarmag.pdf', [
					'organization' => $this->organization
				]);

				(new Email())->sendEmail(
					'approved_registration',
					$this->person->getEmail()->email,
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
			} catch (Exception $e) {
				throw $e;
				//Continue to next event on error
			}

			$this->isActive = 1;
			$this->save();
			if ($this->organization) {
				$this->organization->isActive = 1;
				$this->save();
			}
		}
	}


}
