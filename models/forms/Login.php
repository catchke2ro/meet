<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Class Login
 *
 * Login form model
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Login extends Model {

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var  string
	 */
	public $password;

	/**
	 * @var string
	 */
	private $user;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			// email and password are both required
			[['email', 'password'], 'required'],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
		];
	}


	/**
	 * Validates the password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array  $params    the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params) {
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError($attribute, 'Hibás felhasználónév vagy jelszó!');
			}
		}
	}


	/**
	 * Logs in a user using the provided email and password.
	 *
	 * @return bool whether the user is logged in successfully
	 */
	public function login() {
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser());
		}

		return false;
	}


	/**
	 * Finds user by [[email]]
	 *
	 * @return User|null
	 */
	protected function getUser() {
		if ($this->user === null) {
			$this->user = User::findByEmail($this->email);
		}

		return $this->user;
	}


}