<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\components\Email;
use app\components\Pdf;
use app\models\Organization;
use Yii;
use yii\base\InvalidConfigException;
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
	public function options($actionID): array {
		return [];
	}


	/**
	 * @return void
	 * @throws InvalidConfigException
	 */
	public function actionTest() {
		/** @var Organization $org */
		$org = Organization::findOne(4);
		$person = $org->meetReferee;

		$pdfFilename = (new Pdf())->generatePdf('@app/views/pdf/mustar', 'Mustarmag.pdf', [
			'organization' => $org
		]);

		rename($pdfFilename, Yii::$app->basePath . '/web/Mustarmag.pdf');

		return;

		(new Email())->sendEmail(
			'approved_registration',
			$person->getEmail()->email,
			'MEET Értesítő sikeres regisztrációról',
			['person' => $person, 'organization' => $org],
			[$pdfFilename]
		);
	}


}
