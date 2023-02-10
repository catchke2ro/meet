<?php

namespace app\modules\meet\controllers;

use app\modules\meet\models\forms\PostCreate;
use app\modules\meet\models\forms\PostEdit;
use app\models\Post;
use app\modules\meet\models\OrganizationType;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class PostController
 *
 * Post CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class PostsController extends AbstractAdminController {


	/**
	 * @return string
	 */
	public function actionIndex() {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(Post::class);
		}

		return $this->render('index', []);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionCreate() {
		$model = new PostCreate();

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			$model->image = UploadedFile::getInstance($model, 'image');
			if (($post = $model->create())) {
				Yii::$app->session->setFlash('success', 'Modul sikeresen létrehozva');

				return $this->redirect(Url::to('/meet/posts'));
			}
		}

		Yii::$app->view->params['pageClass'] = 'postCreate';

		return $this->render('create', [
			'orgTypes' => OrganizationType::getList(),
			'model'    => $model,
			'allTags'  => $this->getAllTags()
		]);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionEdit($id) {
		if (!($post = Post::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new PostEdit();
		$model->loadPost($post);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			$model->image = UploadedFile::getInstance($model, 'image');
			if (($post = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Modul sikeresen módosítva');

				return $this->redirect(Url::to('/meet/posts'));
			}
		}
		Yii::$app->view->params['pageClass'] = 'postEdit';

		return $this->render('edit', [
			'orgTypes' => OrganizationType::getList(),
			'model'    => $model,
			'allTags'  => $this->getAllTags()
		]);
	}


	/**
	 * @param $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 * @throws Throwable
	 * @throws StaleObjectException
	 */
	public function actionDelete($id) {
		/** @var Post $post */
		if (!($post = Post::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		foreach ($post->orgTypes as $orgType) {
			$orgType->delete();
		}
		$post->delete();

		Yii::$app->session->setFlash('success', 'Modul sikeresen törölve');

		return $this->redirect(Url::to('/meet/posts'));
	}


	/**
	 * @return array
	 */
	private function getAllTags(): array {
		$posts = Post::find()->all();
		$tags = [];
		foreach ($posts as $post) {
			$tags = array_merge($tags, $post->getTagsArray());
		}
		$tags = array_unique(array_filter($tags));

		return $tags;
	}


}