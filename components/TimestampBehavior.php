<?php

namespace app\components;

/**
 * Class TimestampBehavior
 *
 * Custom timestamp behavior with string timestamps
 *
 * @package app\components
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class TimestampBehavior extends \yii\behaviors\TimestampBehavior {


	/**
	 * {@inheritdoc}
	 *
	 * In case, when the [[value]] is `null`, the result of the PHP function [time()](https://secure.php.net/manual/en/function.time.php)
	 * will be used as value.
	 */
	protected function getValue($event) {
		if ($this->value === null) {
			return date('Y-m-d H:i:s');
		}

		return parent::getValue($event);
	}


}
