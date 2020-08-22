<?php

namespace meetbase\models\lutheran;

use yii\db\ActiveRecord;

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
	 * @return string
	 */
	public static function tableName(): string {
		return 'szemely__t__szemely_kategoria';
	}


}
