<?php

namespace app\lib;

use ArrayObject;

/**
 * Class OrgTypes
 *
 * @package app\lib
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class OrgTypes extends ArrayObject {

	/**
	 * @var OrgTypes|null
	 */
	protected static $instance = null;

	/**
	 * TreeLib constructor.
	 */
	public function __construct() {
		$this->exchangeArray([
			1 => 'Gyülekezet',
			2 => 'Iskola',
			3 => 'Óvoda'
		]);
	}


	/**
	 * @return OrgTypes|static|null
	 */
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new static();
		}
		return self::$instance;
	}


}