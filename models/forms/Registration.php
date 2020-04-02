<?php

namespace app\models\forms;

use app\models\Organization;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Class Registration
 *
 * Registration form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Registration extends Model {

	/**
	 * @var string
	 */
	public $username;

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var  string
	 */
	public $password;

	/**
	 * @var  string
	 */
	public $passwordConfirm;

	/**
	 * @var string
	 */
	public $orgName;

	/**
	 * @var string
	 */
	public $orgAddress;

	/**
	 * @var string
	 */
	public $orgPhone;

	/**
	 * @var string
	 */
	public $orgCompanyNumber;

	/**
	 * @var string
	 */
	public $orgTaxNumber;

	/**
	 * @var int
	 */
	public $orgRemoteId;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		$whenRemoteIdEmpty = function (Registration $model) {
			return empty($model->orgRemoteId);
		};

		return [
			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['email', 'unique', 'targetClass' => User::class, 'message' => 'Az e-mail címmel már van regisztráció'],
			['password', 'required'],
			['password', 'string', 'min' => 6],
			['password', 'compare', 'compareAttribute' => 'passwordConfirm'],
			['orgName', 'required', 'when' => $whenRemoteIdEmpty, 'message' => 'Kötelező, amennyiben nem szerepel az adatbázisban'],
			['orgAddress', 'required', 'when' => $whenRemoteIdEmpty, 'message' => 'Kötelező, amennyiben nem szerepel az adatbázisban'],
			['orgPhone', 'required', 'when' => $whenRemoteIdEmpty, 'message' => 'Kötelező, amennyiben nem szerepel az adatbázisban'],
			['orgCompanyNumber', 'safe'],
			['orgTaxNumber', 'safe'],
			['orgRemoteId', 'safe'],
			['passwordConfirm', 'safe'],
		];
	}


	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 */
	public function signup(): ?User {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;
			$organization = new Organization();
			$organization->email = $this->email;
			$organization->name = $this->orgName;
			$organization->address = $this->orgAddress;
			$organization->phone = $this->orgPhone;
			$organization->company_number = $this->orgCompanyNumber;
			$organization->tax_number = $this->orgTaxNumber;
			$organization->remote_id = (int) $this->orgRemoteId;
			$success &= $organization->save();

			$user = new User();
			$user->email = $this->email;
			$user->setPassword($this->password);
			$user->organization_id = $organization->id;
			$user->generateAuthKey();

			$success &= $user->save();
			$transaction->commit();
			return $success ? $user : null;
		} catch (\Exception $exception) {
			$transaction->rollBack();
		}
	}


	/**
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'email'            => 'E-mail cím',
			'password'         => 'Jelszó',
			'passwordConfirm'  => 'Jelszó megerősítése',
			'orgName'          => 'Szervezet neve',
			'orgAddress'       => 'Szervezet címe',
			'orgPhone'         => 'Szervezet telefonszáma',
			'orgCompanyNumber' => 'Szervezet cégjegyzékszáma',
			'orgTaxNumber'     => 'Szervezet adószáma',
			'orgRemoteId'      => 'Adatbázis'
		];
	}


}