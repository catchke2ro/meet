<?php

namespace app\models\lutheran;

use meetbase\models\lutheran\Organization as BaseOrganization;

/**
 * Class Organization
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Organization extends BaseOrganization {


	/**
	 * @param string|null $search
	 *
	 * @param bool        $mapKeys
	 *
	 * @return array|\yii\db\ActiveRecord[]
	 */
	public static function getList(?string $search = null, bool $mapKeys = true): array {
		$qb = self::find();
		$qb->select(['id', 'nev']);
		if ($search) {
			$qb->andWhere(['like', 'nev', $search]);
		}
		if ($mapKeys) {
			$orgs = [];
			/** @var Organization $org */
			foreach ($qb->all() as $org) {
				$orgs[$org->id] = $org->nev;
			}

			return $orgs;
		} else {
			return $qb->all();
		}
	}


}
