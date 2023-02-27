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

	/**
	 * @var  string
	 */
	public $password;

	/**
	 * @var  string
	 */
	public $passwordConfirm;

	/**
	 * @var  string
	 */
	public $token;

	/**
	 * @var string
	 */
	public $recaptcha_response;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['password', 'required'],
			['password', 'string', 'min' => 6],
			['password', 'compare', 'compareAttribute' => 'passwordConfirm'],
			['recaptcha_response', 'required', 'message' => 'CAPTCHA hiba'],
			['recaptcha_response', recaptcha::class],
			['passwordConfirm', 'safe'],
			['token', 'safe'],
		];
	}


	/**
	 * Signs user up.
	 *
	 * @return bool|array|null
	 */
	public function reset() {
		if (!$this->validate()) {
			return false;
		}

		return password_hash($this->password, PASSWORD_DEFAULT);
	}


	/**
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'password'        => 'Jelszó',
			'passwordConfirm' => 'Jelszó megerősítése'
		];
	}

}
