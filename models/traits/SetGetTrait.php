<?php

namespace app\models\traits;

/**
 *
 */
trait SetGetTrait {


	/**
	 * @param string $name
	 * @param        $value
	 *
	 * @return void
	 */
	public function __set($name, $value): void {
		$name = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
		parent::__set($name, $value);
	}


	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get($name) {
		$getter = 'get' . $name;
		if (!method_exists($this, $getter)) {
			$name = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
		}

		return parent::__get($name);
	}


}
