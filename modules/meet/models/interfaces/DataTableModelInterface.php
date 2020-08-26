<?php

namespace app\modules\meet\models\interfaces;

/**
 * Interface DataTableModeInterface
 * @package app\models\interfaces
 */
interface DataTableModelInterface {


	/**
	 * @return array data
	 */
	public function toDataTableArray(): array;


	/**
	 * @return mixed
	 */
	public function getDataTableActions(): array;


	/**
	 * @return array
	 */
	public static function getTextSearchColumns(): array;


	/**
	 * @return array
	 */
	public static function getOrderableColumns(): array;


}
