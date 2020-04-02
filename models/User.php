<?php

namespace app\models;

use app\components\TimestampBehavior;
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
 * @property int          $id
 * @property string       $password
 * @property string       $password_reset_token
 * @property string       $email
 * @property string       $auth_key
 * @property integer      $status
 * @property string       $organization_id
 * @property Organization $organization
 * @property string       $created_at
 * @property string       $updated_at
 */
class User extends ActiveRecord implements IdentityInterface {


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


}
