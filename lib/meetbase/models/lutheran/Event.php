<?php

namespace meetbase\models\lutheran;

use meetbase\models\traits\SharedModelTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * Class Contact
 *
 * @property int          $id
 * @property string       $erv_kezdet
 * @property string       $erv_veg_varhato
 * @property string       $erv_veg
 * @property int          $erv_allapot
 * @property int          $ref_tipus_id
 * @property int          $ref_szervegyseg_id
 * @property int          $ref_szemely_id
 * @property int          $ref_esemeny_id
 * @property int          $ref1_id
 * @property int          $ref2_id
 * @property int          $ref3_id
 * @property int          $ref4_id
 * @property int          $ref5_id
 * @property int          $ref6_id
 * @property int          $ref7_id
 * @property int          $ref8_id
 * @property int          $ref9_id
 * @property int          $ref10_id
 * @property string       $ertek1
 * @property string       $ertek2
 * @property string       $ertek3
 * @property string       $ertek4
 * @property string       $ertek5
 * @property string       $ertek6
 * @property string       $ertek7
 * @property string       $ertek8
 * @property string       $ertek9
 * @property string       $ertek10
 * @property Person       $person
 * @property Organization $organization
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
abstract class Event extends ActiveRecord {

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
		return 'esemeny__t__esemeny';
	}


	/**
	 * Get org type
	 */
	public function getPerson() {
		return $this->hasOne($this->getModelClass(Person::class), ['id' => 'ref_szemely_id']);
	}


	/**
	 * Get org type
	 */
	public function getOrganization() {
		return $this->hasOne($this->getModelClass(Organization::class), ['id' => 'ref_szervegyseg_id']);
	}


}
