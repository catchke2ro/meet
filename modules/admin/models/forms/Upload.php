<?php

namespace app\modules\admin\models\forms;

use app\models\Post;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class Upload
 *
 * Upload endpoint
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Upload extends Model {

	const TYPE_POST = 'post';

	public UploadedFile|string|null $file = null;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
		return [
			['file', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'checkExtensionByMimeType' => false],
		];
	}


	/**
	 * @return string|null
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
		$this->file->saveAs($dir . '/' . $newName);

		return str_replace(Yii::$app->getBasePath() . '/web', '', $dir) . '/' . $newName;
	}


	/**
	 * @param string|null $type
	 *
	 * @return string
	 */
	public static function getUploadDir(?string $type = null): string {
		$basePath = Yii::$app->getBasePath();

		return match ($type) {
			self::TYPE_POST => Post::getImageBasePath(),
			default => $basePath . '/web/upload',
		};
	}


	/**
	 * @param UploadedFile $file
	 *
	 * @return string
	 */
	public static function renameFile(UploadedFile $file): string {
		return strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '', $file->baseName)) . '.' . $file->extension;
	}


}
