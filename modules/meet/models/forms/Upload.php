<?php

namespace app\modules\meet\models\forms;

use app\models\Post;
use Exception;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class Upload
 *
 * Upload endpoint
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Upload extends Model {

	const TYPE_POST = 'post';

	/**
	 * @var UploadedFile
	 */
	public $file;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['file', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'checkExtensionByMimeType' => false],
		];
	}


	/**
	 * @return Post|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function upload(): ?string {
		if (!$this->validate()) {
			return null;
		}

		$type = null;
		if (Yii::$app->request->getHeaders()->has('X-Upload-Type')) {
			$type = Yii::$app->request->getHeaders()->get('X-Upload-Type');
		}
		$dir = self::getUploadDir($type);

		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}
		$newName = self::renameFile($this->file);
		$this->file->saveAs($dir.'/'.$newName);

		return str_replace(Yii::$app->getBasePath().'/web', '', $dir).'/'.$newName;
	}


	/**
	 * @param string|null $type
	 *
	 * @return string
	 */
	public static function getUploadDir(?string $type = null): string {
		$basePath = Yii::$app->getBasePath();
		switch ($type) {
			case self::TYPE_POST:
				return Post::getImageBasePath();
			default:
				return $basePath.'/web/upload';
		}
	}


	/**
	 * @param UploadedFile $file
	 *
	 * @return string
	 */
	public static function renameFile(UploadedFile $file): string {
		return strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '', $file->baseName)).'.'.$file->extension;
	}


}
