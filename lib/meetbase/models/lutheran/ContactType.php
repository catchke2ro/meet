<?php

namespace meetbase\models\lutheran;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * Class ContactType
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int    $id
 * @property string $nev
 */
abstract class ContactType extends ActiveRecord {

	const ID_ADDRESS = 7;
	const ID_PHONE = 2;
	const ID_EMAIL = 1;
	const ID_GPS = 15;


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
		return 'elerhetoseg__t__elerhetoseg_tipus';
	}


}
