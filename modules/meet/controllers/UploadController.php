<?php

namespace app\modules\meet\controllers;

use app\modules\meet\models\forms\Upload;
use Exception;
use yii\web\UploadedFile;

/**
 * Class UploadController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class UploadController extends AbstractAdminController {


	/**
	 * @return string
	 * @throws Exception
	 */
	public function actionIndex() {
		$model = new Upload();
		$model->file = UploadedFile::getInstanceByName('upload');
		$fileUrl = $model->upload();
		if (!$fileUrl) {
			return $this->asJson([
				'error' => [
					'message' => 'Sikertelen feltöltés'
				]
			]);
		}
		return $this->asJson([
			'url' => $fileUrl
		]);
	}


}
