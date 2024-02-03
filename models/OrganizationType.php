<?php

namespace app\models;

/**
 * Class OrganizationType
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int    $id
 * @property string $name
 * @property string $slug
 */
class OrganizationType extends BaseModel {

	/**
	 * @var array
	 */
	private static array $listCache;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'organization_types';
	}


	/**
	 * @return array
	 */
	public static function getList(): array {
		return array_map(fn(OrganizationType $orgType) => $orgType->name, self::getModelList());
	}


	/**
	 * @return array
	 */
	public static function getModelList(): array {
		if (!isset(self::$listCache)) {
			self::$listCache = [];
			/** @var OrganizationType $organizationType */
			foreach (self::find()->all() ?: [] as $organizationType) {
				self::$listCache[$organizationType->id] = $organizationType;
			}
		}

		return self::$listCache;
	}


}
