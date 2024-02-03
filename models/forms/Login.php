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

	public ?string $email = null;

	public ?string $password = null;

	private ?string $user = null;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
		return [
			[['email', 'password'], 'required'],
			['password', 'validatePassword'],
			['email', 'validateRegistration'],
		];
	}


	/**
	 * Validates the password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array  $params    the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params): void {
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
	 */
	public function validateRegistration(string $attribute): bool {
		if (!$this->hasErrors()) {
			/** @var User $user */
			if (!($user = $this->getUser())) {
				return false;
			}

			if ($this->getUser()->isAdmin()) {
				return true;
			}
			$registrationValid = $user->validateRegistration();
			switch ($registrationValid) {
				case 0:
					$this->addError($attribute, 'Hibás felhasználónév vagy jelszó!');

					return false;
				case 1:
					$this->addError($attribute, 'A regisztráció még aktiválásra vár');

					return false;
			}

			return true;
		}

		return false;
	}


	/**
	 * Logs in a user using the provided email and password.
	 *
	 * @return bool whether the user is logged in successfully
	 */
	public function login(): bool {
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
	protected function getUser(): ?User {
		if ($this->user === null) {
			$this->user = User::findOne(['email' => $this->email]);
		}

		return $this->user;
	}


}
