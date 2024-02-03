<?php

namespace app\modules\admin\controllers;

use app\models\QuestionCategory;
use app\models\QuestionItem;
use app\modules\admin\models\forms\QuestionItemCreate;
use app\modules\admin\models\forms\QuestionItemEdit;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class QuestionItemsController
 *
 * Question category CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionItemsController extends AbstractAdminController {


	/**
	 * @param int $categoryId
	 *
	 * @return Response|string
	 * @throws HttpException
	 */
	public function actionIndex(int $categoryId): Response|string {
		if (!($category = QuestionCategory::findOne(['id' => $categoryId]))) {
			throw new HttpException(404);
		}

		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(QuestionItem::class, function (ActiveQuery $qb) use ($category) {
				$qb->andWhere(['question_category_id' => $category->id]);
			});
		}

		return $this->render('index', [
			'category' => $category
		]);
	}


	/**
	 * @param int $categoryId
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionCreate(int $categoryId): Response|string {
		if (!($category = QuestionCategory::findOne(['id' => $categoryId]))) {
			throw new HttpException(404);
		}

		$model = new QuestionItemCreate($category);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionItem = $model->create())) {
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen létrehozva');

				return $this->redirect(Url::to('/admin/question-items?categoryId=' . $categoryId));
			}
		}

		Yii::$app->view->params['pageClass'] = 'questionItemCreate';

		return $this->render('create', [
			'model'    => $model,
			'category' => $category
		]);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionEdit(int $id): Response|string {
		if (!($questionItem = QuestionItem::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new QuestionItemEdit($questionItem->category);
		$model->loadQuestionItem($questionItem);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionItem = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen módosítva');

				return $this->redirect(Url::to('/admin/question-items?categoryId=' . $questionItem->questionCategoryId));
			}
		}
		Yii::$app->view->params['pageClass'] = 'questionItemEdit';

		return $this->render('edit', [
			'model'    => $model,
			'category' => $questionItem->category
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
		/** @var QuestionItem $questionItem */
		if (!($questionItem = QuestionItem::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		if (!empty($questionItem->options)) {
			Yii::$app->session->setFlash('error', 'A kérdéshez tartoznak opciók, előbb töröld azokat');

			return $this->redirect(Url::to('/admin/question-items?categoryId=' . $questionItem->questionCategoryId));
		}
		$categoryId = $questionItem->questionCategoryId;
		$questionItem->delete();

		Yii::$app->session->setFlash('success', 'Kérdés sikeresen törölve');

		return $this->redirect(Url::to('/admin/question-items?categoryId=' . $categoryId));
	}


}
