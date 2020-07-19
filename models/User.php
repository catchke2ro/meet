<?php

namespace app\models;

use app\components\TimestampBehavior;
use app\models\interfaces\DataTableModelInterface;
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
 * @property int                  $id
 * @property string               $password
 * @property string               $password_reset_token
 * @property string               $email
 * @property string               $name
 * @property string               $auth_key
 * @property bool                 $is_approved_admin
 * @property bool                 $is_approved_boss
 * @property string               $organization_id
 * @property Organization         $organization
 * @property bool                 $is_admin
 * @property string               $created_at
 * @property string               $updated_at
 * @property UserCommitmentFill[] $commitmentFills
 */
class User extends ActiveRecord implements IdentityInterface, DataTableModelInterface {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{users}}';
	}


	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			TimestampBehavior::class,
		];
	}


	/**
	 * @return array
	 */
	public function rules() {
		return [];
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrganization() {
		return $this->hasOne(Organization::class, ['id' => 'organization_id']);
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
	 * @return bool
	 */
	public function isAdmin(): bool {
		return $this->is_admin;
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
		return Yii::$app->security->validatePassword($password, $this->password);
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


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {
		return [
			'id'                => $this->id,
			'email'             => $this->email,
			'name'              => $this->name,
			'is_approved_admin' => $this->is_approved_admin,
			'is_approved_boss'  => $this->is_approved_boss,
			'organization_id'   => $this->organization_id,
			'organization'      => $this->organization,
			'is_admin'          => $this->is_admin,
			'created_at'        => $this->created_at,
			'updated_at'        => $this->updated_at,
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'edit'   => '<a href="/admin/users/edit/' . $this->id . '" class="fa fa-pencil" title="Szereksztés"></a>',
			'delete' => '<a href="/admin/users/delete/' . $this->id . '" class="fa fa-trash" title="Szereksztés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getTextSearchColumns(): array {
		return [
			'email',
			'name'
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getOrderableColumns(): array {
		return [
			'id',
			'email',
			'name',
			'is_approved_admin',
			'is_approved_boss',
			'created_at',
			'updated_at'
		];
	}


}
