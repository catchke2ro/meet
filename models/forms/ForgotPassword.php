<?php

namespace app\models\forms;

use app\components\validators\recaptcha;
use yii\base\Model;

/**
 * Class ForgotPassword
 *
 * Registration form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class ForgotPassword extends Model {

	public ?string $email = null;

	public ?string $recaptchaResponse = null;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
		return [
			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['recaptchaResponse', 'required', 'message' => 'CAPTCHA hiba'],
			['recaptchaResponse', recaptcha::class],
		];
	}


	/**
	 * Signs user up.
	 *
	 * @return bool|string
	 */
	public function submit(): bool|string {
		if (!$this->validate()) {
			return false;
		}

		return $this->email;
	}


	/**
	 * @return array
	 */
	public function attributeLabels(): array {
		return [
			'email' => 'E-mail cím'
		];
	}


}
