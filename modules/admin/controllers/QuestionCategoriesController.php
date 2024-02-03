<?php

namespace app\modules\admin\controllers;

use app\models\OrganizationType;
use app\models\QuestionCategory;
use app\modules\admin\models\forms\QuestionCategoryCreate;
use app\modules\admin\models\forms\QuestionCategoryEdit;
use Exception;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class QuestionCategories
 *
 * Question category CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionCategoriesController extends AbstractAdminController {


	/**
	 * @return Response|string
	 */
	public function actionIndex(): Response|string {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(QuestionCategory::class);
		}

		return $this->render('index', []);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionCreate(): Response|string {
		$model = new QuestionCategoryCreate();

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionCategory = $model->create())) {
				Yii::$app->session->setFlash('success', 'Kérdés kategória sikeresen létrehozva');

				return $this->redirect(Url::to('/admin/question-categories'));
			}
		}

		Yii::$app->view->params['pageClass'] = 'questionCategoryCreate';

		return $this->render('create', [
			'orgTypes' => OrganizationType::getList(),
			'model'    => $model,
		]);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionEdit(int $id): Response|string {
		if (!($questionCategory = QuestionCategory::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new QuestionCategoryEdit();
		$model->loadQuestionCategory($questionCategory);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionCategory = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Kérdés kategória sikeresen módosítva');

				return $this->redirect(Url::to('/admin/question-categories'));
			}
		}
		Yii::$app->view->params['pageClass'] = 'questionCategoryEdit';

		return $this->render('edit', [
			'orgTypes' => OrganizationType::getList(),
			'model'    => $model,
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
		/** @var QuestionCategory $questionCategory */
		if (!($questionCategory = QuestionCategory::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		if (!empty($questionCategory->items)) {
			Yii::$app->session->setFlash('error', 'A kérdés kategóriához tartozik kérdés, ezért nem törölhető');

			return $this->redirect(Url::to('/admin/question-categories'));
		}
		foreach ($questionCategory->orgTypes as $orgType) {
			$orgType->delete();
		}
		$questionCategory->delete();

		Yii::$app->session->setFlash('success', 'Kérdés kategória sikeresen törölve');

		return $this->redirect(Url::to('/admin/question-categories'));
	}


}
