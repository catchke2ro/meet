<?php

namespace app\modules\admin\models\forms;

use app\models\User;
use yii\base\Model;

/**
 * Class UserCreate
 *
 * UserCreate form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class UserCreate extends Model {

	public ?string $email = null;

	public ?string $username = null;

	public ?bool $isActive = null;

	public ?bool $isAdmin = null;

	public ?bool $isApprovedAdmin = null;

	public ?bool $isApprovedBoss = null;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
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
			['isAdmin', 'safe'],
			['isActive', 'safe'],
			['isApprovedAdmin', 'safe'],
			['isApprovedBoss', 'safe'],
		];
	}


	/**
	 * @return array
	 */
	public function attributeLabels(): array {
		return [
			'username'        => 'Felhasználónév',
			'email'           => 'E-mail cím',
			'isActive'        => 'Aktív',
			'isAdmin'         => 'Adminisztrátor',
			'isApprovedAdmin' => 'Adminisztártor által elfogadva',
			'isApprovedBoss'  => 'Vezető által elfogadva'
		];
	}


}
