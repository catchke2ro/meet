<?php

namespace meetbase\models\lutheran;

use meetbase\models\traits\SharedModelTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * Class Person
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int              $id
 * @property int              $ref_kategoria_id
 * @property string           $nev_elotag
 * @property string           $nev
 * @property int              $vuid
 * @property string           $felhasznalonev
 * @property bool             $erv_allapot
 * @property PersonCategory   $personCategory
 * @property \app\models\User $user
 * @property Contact          $emailContact
 */
abstract class Person extends ActiveRecord {

	use SharedModelTrait;


	/**
	 * @return object|Connection|null
	 * @throws InvalidConfigException
	 */
	public static function getDb() {
		return Yii::$app->get('dbtk');
	}


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'szemely__t__szemely';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getPersonCategory() {
		return $this->hasOne($this->getModelClass(PersonCategory::class), ['id' => 'ref_kategoria_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getEmailContact() {
		return $this->hasOne($this->getModelClass(Contact::class), [
			'ref_szemely_id' => 'id'
		])->andOnCondition([
			'ref_tipus_id' => ContactType::ID_EMAIL
		]);
	}


	/**
	 * Get org type
	 */
	public function getEvents() {
		return $this->hasMany($this->getModelClass(Event::class), ['ref_szemely_id' => 'id']);
	}


	/**
	 * @return string|null
	 */
	public function getEmail(): ?string {
		return $this->emailContact ? $this->emailContact->ertek1 : null;
	}


}
