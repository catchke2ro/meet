<?php

namespace meetbase\models\lutheran;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * Class PersonCategory
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int    $id
 * @property string $nev
 * @property string $rovidnev
 */
abstract class PersonCategory extends ActiveRecord {


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
		return 'szemely__t__szemely_kategoria';
	}


}
