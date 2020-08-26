<?php

namespace app\modules\meet\controllers;

use app\modules\meet\models\forms\QuestionCategoryCreate;
use app\modules\meet\models\forms\QuestionCategoryEdit;
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
 * Class QuestionCategories
 *
 * Question category CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionCategoriesController extends AbstractAdminController {


	/**
	 * @return string
	 */
	public function actionIndex() {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(QuestionCategory::class);
		}

		return $this->render('index', []);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionCreate() {
		$model = new QuestionCategoryCreate();

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionCategory = $model->create())) {
				Yii::$app->session->setFlash('success', 'Kérdés kategória sikeresen létrehozva');

				return $this->redirect(Url::to('/meet/question-categories'));
			}
		}

		Yii::$app->view->params['pageClass'] = 'questionCategoryCreate';

		return $this->render('create', [
			'orgTypes' => OrganizationType::getList(),
			'model'    => $model,
		]);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionEdit($id) {
		if (!($questionCategory = QuestionCategory::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new QuestionCategoryEdit();
		$model->loadQuestionCategory($questionCategory);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionCategory = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Kérdés kategória sikeresen módosítva');

				return $this->redirect(Url::to('/meet/question-categories'));
			}
		}
		Yii::$app->view->params['pageClass'] = 'questionCategoryEdit';

		return $this->render('edit', [
			'orgTypes' => OrganizationType::getList(),
			'model'    => $model,
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
		/** @var QuestionCategory $questionCategory */
		if (!($questionCategory = QuestionCategory::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		foreach ($questionCategory->orgTypes as $orgType) {
			$orgType->delete();
		}
		$questionCategory->delete();

		Yii::$app->session->setFlash('success', 'Kérdés kategória sikeresen törölve');

		return $this->redirect(Url::to('/meet/question-categories'));
	}


}
