<?php

namespace app\commands;

use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class CronController extends Controller {


	/**
	 * @param string $actionID
	 *
	 * @return array|string[]
	 */
	public function options($actionID) {
		return [];
	}


}
