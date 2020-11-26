<?php

namespace app\modules\meet\models\forms;

use app\models\Post;
use Exception;
use Yii;

/**
 * Class PostEdit
 *
 * PostEdit form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class PostEdit extends PostCreate {

	/**
	 * @var Post|mixed
	 */
	public $post;


	/**
	 * @param Post $post
	 */
	public function loadPost(Post $post) {
		$this->post = $post;
		$this->title = $post->title;
		$this->intro = $post->intro;
		$this->text = $post->text;
		$this->image = $post->image;
		$this->date = $post->date;
		$this->order = $post->order;
		$this->tags = implode("\n", $post->getTagsArray());
	}


	/**
	 * Signs post up.
	 *
	 * @return Post|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function edit(): ?Post {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$this->post->title = $this->title;
			$this->post->intro = $this->intro;
			$this->post->text = $this->text;
			if ($this->image) {
				$fileName = Upload::renameFile($this->image);
				$this->image->saveAs(Upload::getUploadDir(Upload::TYPE_POST).'/'.$fileName);
				$this->post->image = $fileName;
			}
			$this->post->date = $this->date;
			$this->post->order = $this->order;
			$this->post->tags = json_encode(array_values(explode("\n", $this->tags)));
			$success &= $this->post->save();

			$transaction->commit();

			return $success ? $this->post : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}