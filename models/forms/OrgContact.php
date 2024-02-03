<?php

namespace app\models\forms;

use app\components\validators\recaptcha;
use app\models\Contact;
use app\models\Organization;
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

	public ?int $orgId = null;

	public ?string $email = null;

	public ?string $name = null;

	public ?string $message = null;

	public ?string $recaptchaResponse = null;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
		return [
			['name', 'trim'],
			['name', 'required'],
			['name', 'string', 'max' => 1024],
			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['name', 'string', 'max' => 1024],
			['message', 'required'],
			['recaptchaResponse', 'required', 'message' => 'CAPTCHA hiba'],
			['recaptchaResponse', recaptcha::class],
			['orgId', 'required'],
			['orgId', 'orgIdExists']
		];
	}


	/**
	 * @param $value
	 */
	public function orgIdExists($value): void {
		if (Organization::findOne(['id' => $value])) {
			$this->addError('orgId', 'Érvénytelen adatok');
		}
	}


	/**
	 * Signs user up.
	 * @return Contact|bool
	 */
	public function contact(): Contact|bool {
		if (!$this->validate()) {
			return false;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$contact = new Contact();
			$contact->email = $this->email;
			$contact->name = $this->name;
			$contact->message = $this->message;
			$contact->organizationId = $this->orgId;
			$contact->date = (new \DateTime())->format('Y-m-d H:i:s');
			$success = $contact->save();

			$transaction->commit();

			return $success ? $contact : false;
		} catch (\Exception $exception) {
			$transaction->rollBack();
		}

		return false;
	}


	/**
	 * @return array
	 */
	public function attributeLabels(): array {
		return [
			'email'   => 'E-mail cím',
			'name'    => 'Név',
			'message' => 'Üzenet',
		];
	}


}
