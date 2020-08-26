<?php

namespace app\modules\meet\controllers;

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
				Yii::$app->session->setFlash('success', 'Vállalás kategória sikeresen létrehozva');
				return $this->redirect(Url::to('/meet/commitment-categories'));
			}
		}

		Yii::$app->view->params['pageClass'] = 'commitmentCategoryCreate';

		return $this->render('create', [
			'orgTypes' => OrganizationType::getList(),
			'questionCategories' => $this->getQuestionCategoryList(),
			'model' => $model
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
				Yii::$app->session->setFlash('success', 'Vállalás kategória sikeresen módosítva');
				return $this->redirect(Url::to('/meet/commitment-categories'));
			}
		}
		Yii::$app->view->params['pageClass'] = 'commitmentCategoryEdit';

		return $this->render('edit', [
			'orgTypes' => OrganizationType::getList(),
			'questionCategories' => $this->getQuestionCategoryList(),
			'model' => $model,
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
