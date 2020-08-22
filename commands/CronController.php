<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\components\Email;
use app\models\lutheran\Event;
use Exception;
use Yii;
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
	 * @var string
	 */
	protected $file;


	/**
	 * @param string $actionID
	 *
	 * @return array|string[]
	 */
	public function options($actionID) {
		return [];
	}


	/**
	 *
	 */
	public function actionSendApprovedRegistrations() {
		/** @var Event[] $notMailedApprovedRegs */
		$notMailedApprovedRegs = Event::find()
			->andWhere(['ref_tipus_id' => Yii::$app->params['event_type_meet_reg_approved']])
			->andWhere(['ertek1' => 1])
			->andWhere(['<>', 'ertek2', 1])
			->all();

		foreach ($notMailedApprovedRegs as $notMailedApprovedReg) {
			if ($notMailedApprovedReg->person && ($email = $notMailedApprovedReg->person->getEmail())) {
				try {
					(new Email())->sendEmail(
						'approved_registration',
						$email,
						'MEET - Regisztráció elfogadva',
						['person' => $notMailedApprovedReg->person]
					);
					$notMailedApprovedReg->ertek2 = 1;
					$notMailedApprovedReg->save();
				} catch(Exception $e) {
					//Continue to next event on error
				}

			}
		}
	}


}
