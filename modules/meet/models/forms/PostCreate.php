<?php

namespace app\modules\meet\models\forms;

use app\models\Post;
use DateTimeInterface;
use Exception;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class PostCreate
 *
 * PostCreate form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class PostCreate extends Model {

	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var string
	 */
	public $intro;

	/**
	 * @var string
	 */
	public $text;

	/**
	 * @var string
	 */
	public $tags;

	/**
	 * @var int
	 */
	public $order;

	/**
	 * @var DateTimeInterface
	 */
	public $date;

	/**
	 * @var UploadedFile
	 */
	public $image;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['title', 'trim'],
			['title', 'required'],
			['intro', 'trim'],
			['intro', 'required'],
			['text', 'trim'],
			['tags', 'required'],
			['tags', 'trim'],
			['order', 'number'],
			['date', 'date', 'format' => 'php:Y-m-d'],
			['image', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'checkExtensionByMimeType' => false],
		];
	}


	/**
	 * @return Post|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function create(): ?Post {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$post = new Post();
			$post->title = $this->title;
			$post->intro = $this->intro;
			$post->text = $this->text;
			if ($this->image) {
				$fileName = Upload::renameFile($this->image);
				$this->image->saveAs(Upload::getUploadDir(Upload::TYPE_POST).'/'.$fileName);
				$post->image = $fileName;
			}
			$post->date = $this->date;
			$post->order = $this->order;
			$post->tags = json_encode(array_values(explode("\n", $this->tags)));
			$success &= $post->save();

			$transaction->commit();

			return $success ? $post : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


	/**
	 * @return array
	 */
	public function attributeLabels() {
		return [
			'title' => 'Cím',
			'intro' => 'Bevezető',
			'text'  => 'Szöveg',
			'image' => 'Kép',
			'date'  => 'Dátum',
			'order' => 'Sorrend',
			'tags'  => 'Címkék'
		];
	}


}