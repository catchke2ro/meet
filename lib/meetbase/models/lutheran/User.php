<?php

namespace meetbase\models\lutheran;

use meetbase\models\traits\SharedModelTrait;
use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                  $vuid
 * @property string               $id
 * @property string               $crypt
 * @property string               $email
 * @property string               $name
 * @property Organization         $organization
 * @property Person               $person
 * @property UserCommitmentFill[] $commitmentFills
 */
abstract class User extends ActiveRecord implements IdentityInterface {

	use SharedModelTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '__users';
	}


	public static function getDb() {
		return Yii::$app->get('dbmail');
	}


	/**
	 * @return mixed|null
	 */
	public function getOrgTypeId(): ?int {
		if (($organization = $this->getOrganization())) {
			return $organization->getOrgType()->id;
		}
		return null;
	}


	/**
	 * @return ActiveQuery
	 */
	public function getPerson() {
		return $this->hasOne($this->getModelClass(Person::class), ['vuid' => 'vuid']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getCommitmentFills() {
		return $this->hasMany(UserCommitmentFill::class, ['user_id' => 'id']);
	}


	/**
	 * @return bool
	 */
	public function hasCommitmentFill(): bool {
		return count($this->commitmentFills) > 0;
	}


	/**
	 * @return UserCommitmentFill|null
	 */
	public function getLatestCommitmentFill(): ?UserCommitmentFill {
		return $this->getCommitmentFills()->orderBy('date DESC')->limit(1)->one();
	}


	/**
	 * @param int|string $id
	 *
	 * @return void|IdentityInterface|null
	 */
	public static function findIdentity($id) {
		return self::findOne(['id' => $id]);
	}


	/**
	 * @param mixed $token
	 * @param null  $type
	 *
	 * @return void|IdentityInterface|null
	 * @throws NotSupportedException
	 */
	public static function findIdentityByAccessToken($token, $type = null) {
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}


	/**
	 * Finds user by email
	 *
	 * @param string $email
	 *
	 * @return static|null
	 */
	public static function findByEmail($email) {
		return static::findOne(['email' => $email]);
	}


	/**
	 * @return int|string|void
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @return string|void
	 */
	public function getAuthKey() {
		return $this->auth_key;
	}


	/**
	 * @param string $authKey
	 *
	 * @return bool|void
	 */
	public function validateAuthKey($authKey) {
		return $this->getAuthKey() === $authKey;
	}


	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 *
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password) {
		return Yii::$app->security->validatePassword($password, $this->crypt);
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
	 * @return Organization|null
	 */
	public function getOrganization(): ?Organization {
		if (!empty(($organizations = $this->getOrganizationsByPositionEvents()))) {
			return reset($organizations);
		}
		return null;
	}


	/**
	 * @return array
	 */
	public function getOrganizationsByPositionEvents(): array {
		$events = $this->getActivePositionEvents();
		return array_map(function (Event $event) {
			return $event->organization;
		}, $events);
	}


	/**
	 * @return mixed|null
	 */
	public function getEvents(): ?ActiveQuery {
		return $this->person ? $this->person->getEvents() : null;
	}


	/**
	 * @return array
	 */
	public function getActivePositionEvents(): array {
		if (!$this->person) {
			return [];
		}
		$qb = $this->getEvents()
			->andOnCondition([
				'ref_tipus_id' => Event::ID_TYPE_POSITION,
				'ref2_id'      => Event::ID_POSITION_MEET_REFERER,
				'erv_allapot'  => 1
			]);

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
				'ref_tipus_id'       => Event::ID_TYPE_MEET_APPROVED,
				'erv_allapot'        => 1,
				'ref_szervegyseg_id' => $organization->id
			]);

		return $qb->one();
	}


	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 *
	 * @throws Exception
	 */
	public function setPassword($password) {
		$this->password = Yii::$app->security->generatePasswordHash($password);
	}


	/**
	 * Generates "remember me" authentication key
	 * @throws Exception
	 */
	public function generateAuthKey() {
		$this->auth_key = Yii::$app->security->generateRandomString();
	}


}
