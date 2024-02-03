<?php

namespace app\modules\admin\controllers;

use app\models\OrganizationType;
use app\models\Post;
use app\modules\admin\models\forms\PostCreate;
use app\modules\admin\models\forms\PostEdit;
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
	 * @return Response|string
	 */
	public function actionIndex(): Response|string {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(Post::class);
		}

		return $this->render('index', []);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionCreate(): Response|string {
		$model = new PostCreate();

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			$model->image = UploadedFile::getInstance($model, 'image');
			if (($post = $model->create())) {
				Yii::$app->session->setFlash('success', 'Modul sikeresen létrehozva');

				return $this->redirect(Url::to('/admin/posts'));
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
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionEdit(int $id): Response|string {
		if (!($post = Post::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new PostEdit();
		$model->loadPost($post);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			$model->image = UploadedFile::getInstance($model, 'image');
			if (($post = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Modul sikeresen módosítva');

				return $this->redirect(Url::to('/admin/posts'));
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
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 * @throws StaleObjectException
	 * @throws Throwable
	 */
	public function actionDelete(int $id): Response|string {
		/** @var Post $post */
		if (!($post = Post::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$post->delete();

		Yii::$app->session->setFlash('success', 'Post sikeresen törölve');

		return $this->redirect(Url::to('/admin/posts'));
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

		return array_unique(array_filter($tags));
	}


}
