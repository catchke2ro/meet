<?php

namespace app\lib\enums;

use Yii;

/**
 * Class OrgType
 *
 * @author Adam Balint <catchke2ro@miheztarto.hu>
 */
enum OrgType: string {

	case Diaconia = 'diakonia';
	case Church = 'gyulekezet';
	case Office = 'iroda';
	case Library = 'konyvtar';
	case Education = 'oktatas';
	case Accommodation = 'szallas';


	/**
	 * @return string[]
	 */
	public static function getList(): array {
		return [
			self::Church->value        => Yii::t('meet', 'Gyülekezet'),
			self::Diaconia->value      => Yii::t('meet', 'Diakónia'),
			self::Office->value        => Yii::t('meet', 'Iroda'),
			self::Library->value       => Yii::t('meet', 'Könyvtár'),
			self::Education->value     => Yii::t('meet', 'Oktatás'),
			self::Accommodation->value => Yii::t('meet', 'Szállás')
		];
	}


}
