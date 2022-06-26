<?php

namespace app\modules\meet\models\forms;

use app\models\User;
use yii\base\Model;

/**
 * Class UserCreate
 *
 * UserCreate form
 *
 * @package app\models\forms
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
	public $username;

	/**
	 * @var int
	 */
	public $orgRemoteId;

	/**
	 * @var boolean
	 */
	public $isActive;

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
		return [
			['username', 'trim'],
			['username', 'required'],
			['username', 'string', 'max' => 255],
			['username', 'unique', 'targetClass' => User::class, 'message' => 'A felhasználónévvel címmel már létezik felhasználó'],
			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['email', 'unique', 'targetClass' => User::class, 'message' => 'Az e-mail címmel már létezik felhasználó'],
			['orgRemoteId', 'safe'],
			['isAdmin', 'safe'],
			['isActive', 'safe'],
			['isApprovedAdmin', 'safe'],
			['isApprovedBoss', 'safe'],
		];
	}


	/**
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'username'        => 'Felhasználónév',
			'email'           => 'E-mail cím',
			'orgRemoteId'     => 'Adatbázis',
			'isActive'        => 'Aktív',
			'isAdmin'         => 'Adminisztrátor',
			'isApprovedAdmin' => 'Adminisztártor által elfogadva',
			'isApprovedBoss'  => 'Vezető által elfogadva'
		];
	}


}
