<?php

namespace app\modules\admin\controllers;

use app\models\CommitmentCategory;
use app\models\OrganizationType;
use app\models\QuestionCategory;
use app\modules\admin\controllers\traits\ReorderTrait;
use app\modules\admin\models\forms\CommitmentCategoryCreate;
use app\modules\admin\models\forms\CommitmentCategoryEdit;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class CommitmentCategories
 *
 * Commitment category CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentCategoriesController extends AbstractAdminController {

	use ReorderTrait;


	/**
	 * @return Response|string
	 */
	public function actionIndex(): Response|string {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(CommitmentCategory::class);
		}

		return $this->render('index', []);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionCreate(): Response|string {
		$model = new CommitmentCategoryCreate();

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($commitmentCategory = $model->create())) {
				$commitmentCategory->organizeCategories();
				Yii::$app->session->setFlash('success', 'Vállalás kategória sikeresen létrehozva');

				if (!$this->request->isAjax) {
					return $this->redirect(Url::to('/admin/commitment-categories'));
				}
			} else {
				$this->response->setStatusCode(422);
			}
		}

		Yii::$app->view->params['pageClass'] = 'commitmentCategoryCreate';

		return $this->render('create', [
			'orgTypes'           => OrganizationType::getList(),
			'questionCategories' => $this->getQuestionCategoryList(),
			'model'              => $model
		]);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionEdit(int $id): Response|string {
		if (!($commitmentCategory = CommitmentCategory::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new CommitmentCategoryEdit();
		$model->loadCommitmentCategory($commitmentCategory);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($commitmentCategory = $model->edit())) {
				$commitmentCategory->organizeCategories();
				Yii::$app->session->setFlash('success', 'Vállalás kategória sikeresen módosítva');

				if (!$this->request->isAjax) {
					return $this->redirect(Url::to('/admin/commitment-categories'));
				}
			} else {
				$this->response->setStatusCode(422);
			}
		}
		Yii::$app->view->params['pageClass'] = 'commitmentCategoryEdit';

		return $this->render('edit', [
			'orgTypes'           => OrganizationType::getList(),
			'questionCategories' => $this->getQuestionCategoryList(),
			'model'              => $model,
		]);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 * @throws Throwable
	 * @throws StaleObjectException
	 */
	public function actionDelete(int $id): Response|string {
		/** @var CommitmentCategory $commitmentCategory */
		if (!($commitmentCategory = CommitmentCategory::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		if (!empty($commitmentCategory->items)) {
			Yii::$app->session->setFlash('error', 'A vállalás kategóriához tartozik vállalás, ezért nem törölhető');

			return $this->redirect(Url::to('/admin/commitment-categories'));
		}
		foreach ($commitmentCategory->orgTypes as $orgType) {
			$orgType->delete();
		}
		$commitmentCategory->delete();

		Yii::$app->session->setFlash('success', 'Vállalás kategória sikeresen törölve');

		return $this->redirect(Url::to('/admin/commitment-categories'));
	}


	/**
	 * @param int    $id
	 * @param string $direction
	 *
	 * @throws HttpException
	 */
	public function actionReorder(int $id, string $direction): void {
		/** @var CommitmentCategory $commitmentItem */
		if (!($commitmentCategory = CommitmentCategory::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}

		$categories = CommitmentCategory::find()->orderBy(['order' => SORT_ASC, 'id' => SORT_DESC])->all();

		$this->doReorder($commitmentCategory, $categories, null, $direction);
		$commitmentCategory->organizeCategories();
	}


	/**
	 * @return array
	 */
	private function getQuestionCategoryList(): array {
		$questionCategories = [];
		/** @var QuestionCategory $questionCategory */
		foreach (QuestionCategory::find()->orderBy('name asc')->all() as $questionCategory) {
			$questionCategories[$questionCategory->id] = $questionCategory->name;
		}

		return $questionCategories;
	}


}
