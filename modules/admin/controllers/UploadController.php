<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\forms\Upload;
use Exception;
use Yii;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * Class UploadController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class UploadController extends AbstractAdminController {


	/**
	 * @throws Exception
	 */
	public function actionIndex(): \yii\web\Response {
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


	/**
	 * @param string $file
	 *
	 * @return \yii\web\Response
	 * @throws HttpException
	 */
	public function actionStorageDownload(string $file): \yii\web\Response {
		$rootDir = Yii::$app->getBasePath() . '/storage';

		$filePath = $rootDir . '/' . trim($file, '/');
		if (!file_exists($filePath)) {
			throw new HttpException(404);
		}

		return Yii::$app->response->sendFile($filePath);
	}


}
