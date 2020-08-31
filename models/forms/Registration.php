<?php

namespace app\models\forms;

use app\components\validators\recaptcha;
use app\models\lutheran\Contact;
use app\models\lutheran\ContactType;
use app\models\lutheran\Event;
use app\models\lutheran\Organization;
use app\models\lutheran\Person;
use app\models\lutheran\User;
use Exception;
use Yii;
use yii\base\Model;
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

	/**
	 * @var string
	 */
	public $username;

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
	public $namePrefix;

	/**
	 * @var  string
	 */
	public $password;

	/**
	 * @var  string
	 */
	public $passwordConfirm;

	/**
	 * @var string
	 */
	public $orgName;

	/**
	 * @var string
	 */
	public $orgAddressZip;

	/**
	 * @var string
	 */
	public $orgAddressCity;

	/**
	 * @var string
	 */
	public $orgAddressStreet;

	/**
	 * @var string
	 */
	public $orgPhone;

	/**
	 * @var int
	 */
	public $orgRemoteId;

	/**
	 * @var UploadedFile
	 */
	public $pdf;

	/**
	 * @var string
	 */
	public $terms;

	/**
	 * @var string
	 */
	public $terms2;

	/**
	 * @var string
	 */
	public $recaptcha_response;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		$whenRemoteIdEmpty = function (Registration $model) {
			return empty($model->orgRemoteId);
		};

		return [
			['name', 'trim'],
			['name', 'required'],
			['namePrefix', 'trim'],

			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['email', 'unique', 'targetClass' => User::class, 'message' => 'Az e-mail címmel már van regisztráció'],
			//['password', 'required'],
			//['password', 'string', 'min' => 6],
			//['password', 'compare', 'compareAttribute' => 'passwordConfirm'],
			['orgName', 'required', 'when' => $whenRemoteIdEmpty, 'message' => 'Kötelező, amennyiben nem szerepel az adatbázisban'],
			['orgAddressZip', 'required', 'when' => $whenRemoteIdEmpty, 'message' => 'Kötelező, amennyiben nem szerepel az adatbázisban'],
			['orgAddressCity', 'required', 'when' => $whenRemoteIdEmpty, 'message' => 'Kötelező, amennyiben nem szerepel az adatbázisban'],
			['orgAddressStreet', 'required', 'when' => $whenRemoteIdEmpty, 'message' => 'Kötelező, amennyiben nem szerepel az adatbázisban'],
			['orgPhone', 'required', 'when' => $whenRemoteIdEmpty, 'message' => 'Kötelező, amennyiben nem szerepel az adatbázisban'],
			['orgCompanyNumber', 'safe'],
			['orgTaxNumber', 'safe'],
			['orgRemoteId', 'safe'],
			['pdf', 'required'],
			['pdf', 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
			['terms', 'required', 'requiredValue' => 1, 'message' => 'A szabályzat elfogadása kötelező!'],
			['terms2', 'required', 'requiredValue' => 1, 'message' => 'A feltételek elfogadása kötelező!'],
			['recaptcha_response', 'required', 'message' => 'CAPTCHA hiba'],
			['recaptcha_response', recaptcha::class]

			//['passwordConfirm', 'safe'],
		];
	}


	/**
	 * @param array $data
	 * @param null  $formName
	 *
	 * @return bool
	 */
	public function load($data, $formName = null) {
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
	 */
	public function signup() {
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

			if (!$this->orgRemoteId) {
				$organization = new Organization();
				$organization->nev = $this->orgName;
				$organization->ref_regi_id = 0;
				$organization->ref_kategoria_id = Yii::$app->params['new_org_kategoria_id']; //Névtári bejegyzés Küld
				$organization->ref_tipus_id = Yii::$app->params['new_org_default_tipus_id']; //Egyházközszég
				$organization->erv_allapot = Yii::$app->params['new_org_erv_allapot'];
				$success &= $organization->save();

				$orgAddressContact = new Contact();
				$orgAddressContact->ref_tipus_id = ContactType::ID_ADDRESS;
				$orgAddressContact->ref_tabla = Organization::tableName();
				$orgAddressContact->ref_szervegyseg_id = $organization->id;
				$orgAddressContact->ref_id = $organization->id;
				$orgAddressContact->ertek1 = $this->orgAddressZip;
				$orgAddressContact->ertek2 = $this->orgAddressCity;
				$orgAddressContact->ertek3 = $this->orgAddressStreet;
				$success &= $orgAddressContact->save();

				$orgPhoneContact = new Contact();
				$orgPhoneContact->ref_tipus_id = ContactType::ID_PHONE;
				$orgPhoneContact->ref_tabla = Organization::tableName();
				$orgPhoneContact->ref_szervegyseg_id = $organization->id;
				$orgPhoneContact->ref_id = $organization->id;
				$orgPhoneContact->ertek1 = $this->orgPhone;
				$success &= $orgPhoneContact->save();

				$orgEmailContanct = new Contact();
				$orgEmailContanct->ref_tipus_id = ContactType::ID_EMAIL;
				$orgEmailContanct->ref_tabla = Organization::tableName();
				$orgEmailContanct->ref_szervegyseg_id = $organization->id;
				$orgEmailContanct->ref_id = $organization->id;
				$orgEmailContanct->ertek1 = $this->email;
				$success &= $orgEmailContanct->save();

			} else {
				$organization = Organization::findOne(['id' => (int) $this->orgRemoteId]);
			}

			if (!$organization) {
				throw new Exception('Invalid organization');
			}

			$person = new Person();
			$person->ref_kategoria_id = Yii::$app->params['new_person_kategoria_id'];
			$person->nev_elotag = $this->namePrefix;
			$person->nev = $this->name;
			$person->erv_allapot = Yii::$app->params['new_person_erv_allapot'];
			$success &= $person->save();

			$personEmailContanct = new Contact();
			$personEmailContanct->ref_tipus_id = ContactType::ID_EMAIL;
			$personEmailContanct->ref_tabla = Person::tableName();
			$personEmailContanct->ref_szemely_id = $person->id;
			$personEmailContanct->publikus = 0;
			$personEmailContanct->ref_id = $person->id;
			$personEmailContanct->ertek1 = $this->email;
			$success &= $personEmailContanct->save();

			$newPositionEvent = Event::createNewPositionEvent($organization, $person);
			$newRegistrationEvent = Event::createNewRegistrationEvent($organization, $person, $personEmailContanct, $targetFileId);

			$success &= $newPositionEvent->save();
			$success &= $newRegistrationEvent->save();

			$transaction->commit();

			return $success ? [$person->id, $organization->id] : false;
		} catch (\Exception $exception) {
			$transaction->rollBack();
		}
	}


	/**
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'email'            => 'E-mail cím',
			'password'         => 'Jelszó',
			'passwordConfirm'  => 'Jelszó megerősítése',
			'orgName'          => 'Szervezet neve',
			'orgAddressZip'    => 'Irányítószám',
			'orgAddressCity'   => 'Város',
			'orgAddressStreet' => 'Utca, házszám...',
			'orgPhone'         => 'Szervezet telefonszáma',
			'orgCompanyNumber' => 'Szervezet cégjegyzékszáma',
			'orgTaxNumber'     => 'Szervezet adószáma',
			'orgRemoteId'      => 'Adatbázis',
			'pdf'              => 'Meghatalmazás',
			'terms'            => 'Adatkezelési szabályzat',
			'terms2'           => 'Általános Együttműködési Feltételek'
		];
	}


}