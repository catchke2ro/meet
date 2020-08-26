<?php

namespace app\models\forms;

use app\components\validators\recaptcha;
use app\models\lutheran\Event;
use app\models\lutheran\Organization;
use Yii;
use yii\base\Model;

/**
 * Class OrgContact
 *
 * Org-contact form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class OrgContact extends Model {

	/**
	 * @var int
	 */
	public $orgId;

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $message;

	/**
	 * @var string
	 */
	public $recaptcha_response;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['name', 'trim'],
			['name', 'required'],
			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['message', 'required'],
			['recaptcha_response', 'required', 'message' => 'CAPTCHA hiba'],
			['recaptcha_response', recaptcha::class],
			['orgId', 'required'],
			['orgId', 'orgIdExists']
		];
	}


	/**
	 * @param $value
	 */
	public function orgIdExists($value) {
		if (Organization::findOne(['id' => $value])) {
			$this->addError('orgId', 'Érvénytelen adatok');
		}
	}


	/**
	 * Signs user up.
	 *
	 * @return bool|null
	 */
	public function signup() {
		if (!$this->validate()) {
			return false;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$newMessageEvent = Event::createNewMessageEvent($this->orgId, $this->email, $this->name, $this->message);
			$success &= $newMessageEvent->save();
			$transaction->commit();

			return $success;
		} catch (\Exception $exception) {
			$transaction->rollBack();
		}
	}


	/**
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'email'   => 'E-mail cím',
			'name'    => 'Név',
			'message' => 'Üzenet',
		];
	}


}