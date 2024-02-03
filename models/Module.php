<?php

namespace app\models;

use app\models\interfaces\DataTableModelInterface;
use yii\db\ActiveRecord;

/**
 * Class Module
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int    $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $descriptionPdf
 * @property int    $threshold
 */
class Module extends BaseModel implements DataTableModelInterface {

	/**
	 * @var mixed|null
	 */
	protected static $firstModule;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'modules';
	}


	/**
	 * @return \app\models\Module
	 */
	public static function firstModule(): Module {
		if (is_null(self::$firstModule)) {
			self::$firstModule = static::find()->orderBy('threshold ASC')->one();
		}

		return self::$firstModule;
	}


	/**
	 * @return array|ActiveRecord[]
	 */
	public static function getList() {
		$qb = self::find();
		$qb->select(['id', 'name']);
		$modules = [];
		/** @var Module $module */
		foreach ($qb->all() as $module) {
			$modules[$module->id] = $module->name;
		}

		return $modules;
	}


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {
		return [
			'id'        => $this->id,
			'name'      => $this->name,
			'threshold' => $this->threshold,
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'edit' => '<a href="/admin/modules/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>'
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getTextSearchColumns(): array {
		return [
			'name',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getOrderableColumns(): array {
		return [
			'name',
			'threshold',
		];
	}


}
