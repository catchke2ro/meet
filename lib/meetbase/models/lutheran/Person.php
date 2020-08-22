<?php

namespace meetbase\models\lutheran;

use meetbase\models\traits\SharedModelTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Person
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int            $id
 * @property int            $ref_kategoria_id
 * @property string         $nev_elotag
 * @property string         $nev
 * @property int            $vuid
 * @property string         $felhasznalonev
 * @property bool           $erv_allapot
 * @property PersonCategory $personCategory
 * @property User           $user
 */
abstract class Person extends ActiveRecord {

	use SharedModelTrait;


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
	public function getUser() {
		return $this->hasOne($this->getModelClass(User::class), ['vuid' => 'vuid']);
	}


	/**
	 * Get org type
	 */
	public function getEvents() {
		return $this->hasMany($this->getModelClass(Event::class), ['ref_szemely_id' => 'id']);
	}


}
