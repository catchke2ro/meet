<?php

namespace app\models\forms;

use app\components\validators\recaptcha;
use yii\base\Model;

/**
 * Class ResetPassword
 *
 * Registration form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class ResetPassword extends Model {

	public ?string $password;

	public ?string $passwordConfirm;

	public ?string $token;

	public ?string $recaptchaResponse;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
		return [
			['password', 'required'],
			['password', 'string', 'min' => 6],
			['password', 'compare', 'compareAttribute' => 'passwordConfirm'],
			['recaptchaResponse', 'required', 'message' => 'CAPTCHA hiba'],
			['recaptchaResponse', recaptcha::class],
			['passwordConfirm', 'safe'],
			['token', 'safe'],
		];
	}


	/**
	 * Signs user up.
	 *
	 * @return bool|string
	 */
	public function reset(): bool|string {
		if (!$this->validate()) {
			return false;
		}

		return password_hash($this->password, PASSWORD_DEFAULT);
	}


	/**
	 * @return array
	 */
	public function attributeLabels(): array {
		return [
			'password'        => 'Jelszó',
			'passwordConfirm' => 'Jelszó megerősítése'
		];
	}


}
