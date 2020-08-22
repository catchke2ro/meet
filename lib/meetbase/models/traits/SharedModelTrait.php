<?php


namespace meetbase\models\traits;

/**
 * Trait SharedModelTrait
 *
 * @package   meetbase\models\traits
 * @author    SRG Group <dev@srg.hu>
 * @copyright 2020 SRG Group Kft.
 */
trait SharedModelTrait {


	/**
	 * @param string $baseClass
	 *
	 * @return string|string[]|null
	 */
	protected function getModelClass(string $baseClass) {
		return preg_replace('/^meetbase\\\\models\\\\/', 'app\\models\\', $baseClass);
	}


}
