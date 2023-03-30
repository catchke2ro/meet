<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\components\Email;
use app\models\lutheran\Organization;
use app\models\lutheran\Person;
use Yii;
use yii\base\View;
use yii\base\ViewEvent;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class TestController extends Controller {

	/**
	 * @param string $actionID
	 *
	 * @return array|string[]
	 */
	public function options($actionID) {
		return [];
	}


	public function actionTest() {
		$organization = Organization::findOne(['id' => 11801]);

		echo "Pastor general {$organization->getPastorGeneral()?->nev}\n";
		echo "Pastor {$organization->getPastor()?->nev}\n";
		echo "Super {$organization->getSuperintendent()?->nev}\n";
		echo "Meet {$organization->getMeetReferer()?->nev}\n";

		die();


		$person = Person::findOne(['id' => 213766]);
		$email = $person->getEmail();

		(new Email())->sendEmail(
			'new_registration',
			$email,
			'MEET - Új regisztráció',
			['person' => $person]
		);
	}


}
