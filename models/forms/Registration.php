<?php

namespace app\models\forms;

use app\components\validators\recaptcha;
use app\lib\enums\PersonType;
use app\models\Address;
use app\models\Email;
use app\models\Organization;
use app\models\OrganizationType;
use app\models\Person;
use app\models\Phone;
use app\models\User;
use DateTime;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class Registration
 *
 * Registration form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Registration extends Model {

	public ?string $username = null;

	public ?string $refereeEmail = null;

	public ?string $refereeName = null;

	public ?string $password = null;

	public ?string $passwordConfirm = null;

	public ?string $orgName = null;

	public ?string $orgAddressZip = null;

	public ?string $orgAddressCity = null;

	public ?string $orgAddressStreet = null;

	public ?string $orgPhone = null;

	public ?string $orgEmail = null;

	public ?string $orgType = null;

	public ?string $pastorName = null;

	public ?string $pastorEmail = null;

	public ?string $superintendentName = null;

	public string|UploadedFile|null $pdf = null;

	public ?string $terms = null;

	public ?string $terms2 = null;

	public ?string $recaptchaResponse = null;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {

		$emailRules = fn($slug) => [
			[$slug, 'trim'],
			[$slug, 'required'],
			[$slug, 'email'],
			[$slug, 'string', 'max' => 255]
		];
		$nameRules = fn($slug) => [
			[$slug, 'trim'],
			[$slug, 'required'],
			[$slug, 'string', 'max' => 1024]
		];

		return [
			...$nameRules('refereeName'),

			...$emailRules('refereeEmail'),
			['refereeEmail', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email', 'message' => 'Az e-mail címmel már van regisztráció'],

			['password', 'required'],
			['password', 'string', 'min' => 6],
			['password', 'compare', 'compareAttribute' => 'passwordConfirm'],

			['orgName', 'required'],
			['orgName', 'trim'],
			['orgName', 'string', 'max' => 1024],
			['orgAddressZip', 'required'],
			['orgAddressZip', 'trim'],
			['orgAddressZip', 'string', 'max' => 10],
			['orgAddressCity', 'required'],
			['orgAddressCity', 'trim'],
			['orgAddressCity', 'string', 'max' => 255],
			['orgAddressStreet', 'required'],
			['orgAddressStreet', 'trim'],
			['orgAddressStreet', 'string', 'max' => 1024],
			['orgPhone', 'string', 'max' => 100],
			...$emailRules('orgEmail'),
			['orgType', 'required'],
			['orgType', 'in', 'range' => array_keys(OrganizationType::getList())],

			...$nameRules('pastorName'),
			...$emailRules('pastorEmail'),

			...$nameRules('superintendentName'),

			['pdf', 'required'],
			['pdf', 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf', 'checkExtensionByMimeType' => false],
			['terms', 'required', 'requiredValue' => 1, 'message' => 'A szabályzat elfogadása kötelező!'],
			['terms2', 'required', 'requiredValue' => 1, 'message' => 'A feltételek elfogadása kötelező!'],
			['recaptchaResponse', 'required', 'message' => 'CAPTCHA hiba'],
			['recaptchaResponse', recaptcha::class],

			['passwordConfirm', 'safe'],
		];
	}


	/**
	 * @param array $data
	 * @param null  $formName
	 *
	 * @return bool
	 */
	public function load($data, $formName = null): bool {
		$result = parent::load($data, $formName);
		if ($result) {
			$this->pdf = UploadedFile::getInstance($this, 'pdf');
		}

		return $result;
	}


	/**
	 * Signs user up.
	 *
	 * @return bool|array|null
	 * @throws Exception
	 */
	public function signup(): bool|array|null {
		if (!$this->validate()) {
			return false;
		}

		$targetFileId = time() . '_' . uniqid();
		$targetFileName = $targetFileId . '.pdf';
		$targetFile = Yii::$app->getBasePath() . '/storage/authorizations/' . $targetFileName;
		$this->pdf->saveAs($targetFile);

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$organization = new Organization();
			$organization->name = $this->orgName;
			$organization->organizationTypeId = $this->orgType;
			$organization->isActive = false;
			$success &= $organization->save();

			$address = new Address;
			$address->zip = $this->orgAddressZip;
			$address->city = $this->orgAddressCity;
			$address->address = $this->orgAddressStreet;
			$success &= $address->save();
			$organization->link('addresses', $address);

			$phone = new Phone();
			$phone->number = $this->orgPhone;
			$success &= $phone->save();
			$organization->link('phones', $phone);

			$orgEmail = new Email();
			$orgEmail->email = $this->orgEmail;
			$success &= $orgEmail->save();
			$organization->link('emails', $orgEmail);

			$meetReferee = new Person();
			$meetReferee->type = PersonType::MeetReferee;
			$meetReferee->name = $this->refereeName;
			$meetReferee->isActive = true;
			$success &= $meetReferee->save();
			$organization->link('people', $meetReferee);

			$meetRefereeEmail = new Email();
			$meetRefereeEmail->email = $this->refereeEmail;
			$success &= $meetRefereeEmail->save();
			$meetReferee->link('emails', $meetRefereeEmail);

			$pastor = new Person();
			$pastor->type = PersonType::Pastor;
			$pastor->name = $this->pastorName;
			$pastor->isActive = true;
			$success &= $pastor->save();
			$organization->link('people', $pastor);

			$pastorEmail = new Email();
			$pastorEmail->email = $this->pastorEmail;
			$success &= $pastorEmail->save();
			$pastor->link('emails', $pastorEmail);

			$superintendent = new Person();
			$superintendent->type = PersonType::Superintendent;
			$superintendent->name = $this->superintendentName;
			$superintendent->isActive = true;
			$success &= $superintendent->save();
			$organization->link('people', $superintendent);

			$user = new User();
			$user->email = $this->refereeEmail;
			$user->username = $this->generateUsername($this->refereeName);
			$user->password = password_hash($this->password, PASSWORD_DEFAULT);
			$user->registeredAt = (new DateTime())->format('Y-m-d H:i:s');
			$user->isActive = false;
			$user->personId = $meetReferee->id;
			$success &= $user->save();

			$transaction->commit();

			return $success ? [$meetReferee->id, $organization->id, $targetFile] : false;
		} catch (\Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


	/**
	 * @return array
	 */
	public function attributeLabels(): array {
		return [
			'refereeEmail'       => 'E-mail cím',
			'refereeName'        => 'Név',
			'password'           => 'Jelszó',
			'passwordConfirm'    => 'Jelszó megerősítése',
			'orgName'            => 'Szervezet neve',
			'orgAddressZip'      => 'Irányítószám',
			'orgAddressCity'     => 'Város',
			'orgAddressStreet'   => 'Utca, házszám...',
			'orgPhone'           => 'Szervezet telefonszáma',
			'orgEmail'           => 'E-mail',
			'orgType'            => 'Szervezet típusa',
			'pastorName'         => 'Név',
			'pastorEmail'        => 'E-mail cím',
			'superintendentName' => 'Név',
			'pdf'                => 'Meghatalmazás',
			'terms'              => 'Adatkezelési szabályzat',
			'terms2'             => 'Általános Együttműködési Feltételek'
		];
	}


	/**
	 * @param string $name
	 *
	 * @return string
	 */
	protected function generateUsername(string $name): string {
		$usernames = User::find()
			->select('username')
			->asArray()->column();

		preg_match('/^([^\s]+)\s+(.*)$/ui', $name, $match);
		$firstName = implode('', array_map(function ($firstNamePart) {
			return slug(mb_substr($firstNamePart, 0, 1));
		}, array_filter(explode(' ', $match[2]))));
		$lastName = slug(preg_replace('/[^\p{L}]/ui', '', $match[1]));
		$usernameBase = $firstName . $lastName;
		$i = 0;
		do {
			$username = $usernameBase . ($i > 0 ? $i : '');
			$i ++;
		} while (in_array($username, $usernames));

		return $username;
	}


}
