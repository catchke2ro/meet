<?php

namespace app\modules\meet\models;

use app\modules\meet\models\interfaces\DataTableModelInterface;
use meetbase\models\Module as BaseModule;

/**
 * Class Module
 *
 * @package   app\modules\meet\models
 * @author    SRG Group <dev@srg.hu>
 * @copyright 2020 SRG Group Kft.
 */
class Module extends BaseModule implements DataTableModelInterface {


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
			'edit'   => '<a href="/meet/modules/edit?id=' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>'
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
