<?php

namespace meetbase\models\lutheran;

use meetbase\models\traits\SharedModelTrait;
use yii\db\ActiveRecord;

/**
 * Class Contact
 *
 * @property int    $id
 * @property int    $erv_allapot
 * @property int    $ref_tipus_id
 * @property int    $ref_szervegyseg_id
 * @property int    $ref_szemely_id
 * @property int    $publikus
 * @property string $ref_tabla
 * @property int    $ref_id
 * @property string $ertek1
 * @property string $ertek2
 * @property string $ertek3
 * @property string $ertek9
 * @property string $ertek10
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
abstract class Contact extends ActiveRecord {

	use SharedModelTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'elerhetoseg__t__elerhetoseg';
	}


	/**
	 * Get org type
	 */
	public function getOrgType() {
		$this->hasOne($this->getModelClass(ContactType::class), ['id' => 'ref_tipus_id']);
	}


}
