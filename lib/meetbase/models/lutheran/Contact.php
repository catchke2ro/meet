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


	/**
	 * @return string
	 */
	public function getAddressString(): string {
		$parts = [];
		if ($this->ertek1) {
			$parts[] = $this->ertek1;
		}
		if ($this->ertek2) {
			$parts[] = $this->ertek2 . ($this->ertek3 ? ',' : '');
		}
		if ($this->ertek3) {
			$parts[] = $this->ertek3;
		}

		return implode(' ', $parts);
	}


	/**
	 * @return array|null
	 */
	public function getCooridnates(): ?array {
		if (!empty($this->ertek1) && !empty($this->ertek2)) {
			return [
				'lat' => $this->ertek1,
				'lng' => $this->ertek2
			];
		}
		return null;
	}


}
