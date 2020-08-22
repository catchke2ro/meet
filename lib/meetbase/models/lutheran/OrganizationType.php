<?php

namespace meetbase\models\lutheran;

use yii\db\ActiveRecord;

/**
 * Class Organization
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int    $id
 * @property string $nev
 */
abstract class OrganizationType extends ActiveRecord {

	/**
	 * @var array|null
	 */
	private static $listCache = null;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'szervegyseg__t__szervegyseg_tipus';
	}


	/**
	 * @return array
	 */
	public static function getList(): array {
		if (is_null(self::$listCache)) {
			self::$listCache = [];
			foreach (self::find()->all() ?: [] as $organizationType) {
				self::$listCache[$organizationType->id] = $organizationType->nev;
			}
		}

		return self::$listCache;
	}


}
