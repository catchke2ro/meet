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

	const VALID         = 1;
	const NOT_EXISTS    = 2;
	const NOT_ACTIVATED = 3;

	/**
	 * @var string
	 */
	public $username;

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
			// username and password are both required
			[['username', 'password'], 'required'],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
			['username', 'validateRegistration'],
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
	 * Validates the password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array  $params    the additional name-value pairs given in the rule
	 */
	public function validateRegistration($attribute, $params) {
		if (!$this->hasErrors()) {
			/** @var User $user */
			if (!($user = $this->getUser())) {
				return;
			}

			if ($this->getUser()->isAdmin()) {
				return true;
			}
			$registrationValid = $user->validateRegistration();
			switch ($registrationValid) {
				case 0:
					$this->addError($attribute, 'Hibás felhasználónév vagy jelszó!');
				case 1:
					$this->addError($attribute, 'A regisztráció még aktiválásra vár');
			}
		}
	}


	/**
	 * Logs in a user using the provided username and password.
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
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	protected function getUser() {
		if ($this->user === null) {
			$this->user = User::findOne(['username' => $this->username]);
		}

		return $this->user;
	}


}