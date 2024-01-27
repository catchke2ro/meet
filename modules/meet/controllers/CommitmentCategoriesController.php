<?php

namespace app\modules\meet\controllers;

use app\modules\meet\controllers\traits\ReorderTrait;
use app\modules\meet\models\CommitmentCategory;
use app\modules\meet\models\forms\CommitmentCategoryCreate;
use app\modules\meet\models\forms\CommitmentCategoryEdit;
use app\modules\meet\models\OrganizationType;
use app\modules\meet\models\QuestionCategory;
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
	 * @return string
	 */
	public function actionIndex() {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(CommitmentCategory::class);
		}

		return $this->render('index', []);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionCreate() {
		$model = new CommitmentCategoryCreate();

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($commitmentCategory = $model->create())) {
				$commitmentCategory->organizeCategories();
				Yii::$app->session->setFlash('success', 'Vállalás kategória sikeresen létrehozva');

				if (!$this->request->isAjax) {
					return $this->redirect(Url::to('/meet/commitment-categories'));
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
	 * @param $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionEdit($id) {
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
					return $this->redirect(Url::to('/meet/commitment-categories'));
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
	 * @param $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 * @throws Throwable
	 * @throws StaleObjectException
	 */
	public function actionDelete($id) {
		/** @var CommitmentCategory $commitmentCategory */
		if (!($commitmentCategory = CommitmentCategory::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		foreach ($commitmentCategory->orgTypes as $orgType) {
			$orgType->delete();
		}
		$commitmentCategory->delete();

		Yii::$app->session->setFlash('success', 'Vállalás kategória sikeresen törölve');

		return $this->redirect(Url::to('/meet/commitment-categories'));
	}


	/**
	 * @param $id
	 * @param $direction
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionReorder($id, $direction) {
		/** @var CommitmentCategory $commitmentItem */
		if (!($commitmentCategory = CommitmentCategory::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}

		$categories = CommitmentCategory::find()->orderBy(['order' => SORT_ASC, 'id' => SORT_DESC])->all();

		$this->doReorder($commitmentCategory, $categories, null, $direction);
		$commitmentCategory->organizeCategories();
	}


	/**
	 *
	 */
	private function getQuestionCategoryList(): array {
		$questionCategories = [];
		foreach (QuestionCategory::find()->orderBy('name asc')->all() as $questionCategory) {
			$questionCategories[$questionCategory->id] = $questionCategory->name;
		}

		return $questionCategories;
	}


}
