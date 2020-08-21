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
 * @property string $description_pdf
 * @property int    $threshold
 */
class Module extends ActiveRecord implements DataTableModelInterface {


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{meet_modules}}';
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
			'edit'   => '<a href="/admin/modules/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>',
			'delete' => '<a href="/admin/modules/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
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
