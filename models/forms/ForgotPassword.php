<?php

namespace app\models\forms;

use app\components\validators\recaptcha;
use yii\base\Model;

/**
 * Class Registration
 *
 * Registration form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class ForgotPassword extends Model {

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $recaptcha_response;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['recaptcha_response', 'required', 'message' => 'CAPTCHA hiba'],
			['recaptcha_response', recaptcha::class],
		];
	}


	/**
	 * Signs user up.
	 *
	 * @return bool|array|null
	 */
	public function submit() {
		if (!$this->validate()) {
			return false;
		}

		return $this->email;
	}


	/**
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'email' => 'E-mail cím'
		];
	}


}
