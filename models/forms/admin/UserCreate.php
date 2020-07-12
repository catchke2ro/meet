<?php

namespace app\models\forms\admin;

use app\models\Organization;
use app\models\User;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class UserCreate
 *
 * UserCreate form
 *
 * @package app\models\forms\admin
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class UserCreate extends Model {

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $name;

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
	 * @var boolean
	 */
	public $isAdmin;

	/**
	 * @var boolean
	 */
	public $isApprovedAdmin;

	/**
	 * @var boolean
	 */
	public $isApprovedBoss;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		$whenRemoteIdEmpty = function (UserCreate $model) {
			return empty($model->orgRemoteId);
		};

		return [
			['name', 'trim'],
			['name', 'required'],
			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['email', 'unique', 'targetClass' => User::class, 'message' => 'Az e-mail címmel már létezik felhasználó'],
			['password', 'required'],
			['password', 'string', 'min' => 6],
			['password', 'compare', 'compareAttribute' => 'passwordConfirm'],
			['orgName', 'required', 'when' => $whenRemoteIdEmpty, 'message' => 'Kötelező, amennyiben nem szerepel az adatbázisban'],
			['orgAddress', 'required', 'when' => $whenRemoteIdEmpty, 'message' => 'Kötelező, amennyiben nem szerepel az adatbázisban'],
			['orgPhone', 'required', 'when' => $whenRemoteIdEmpty, 'message' => 'Kötelező, amennyiben nem szerepel az adatbázisban'],
			['orgCompanyNumber', 'safe'],
			['orgTaxNumber', 'safe'],
			['orgRemoteId', 'safe'],
			['passwordConfirm', 'safe']
		];
	}


	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function create(): ?User {
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
			$user->name = $this->name;
			$user->setPassword($this->password);
			$user->organization_id = $organization->id;
			$user->is_admin = $this->isAdmin ?: false;
			$user->is_approved_boss = $this->isApprovedBoss ?: false;
			$user->is_approved_admin = $this->isApprovedAdmin ?: false;
			$user->generateAuthKey();

			$success &= $user->save();
			$transaction->commit();

			return $success ? $user : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
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
			'orgRemoteId'      => 'Adatbázis',
			'isAdmin'          => 'Adminisztrátor',
			'isApprovedAdmin'  => 'Adminisztártor által elfogadva',
			'isApprovedBoss'   => 'Vezető által elfogadva'
		];
	}


}