<?php

namespace app\controllers\admin;

use app\lib\OrgTypes;
use app\models\forms\admin\QuestionCategoryEdit;
use app\models\forms\admin\QuestionCategoryCreate;
use app\models\forms\admin\QuestionItemCreate;
use app\models\forms\admin\QuestionItemEdit;
use app\models\QuestionCategory;
use app\models\QuestionItem;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\QueryBuilder;
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
	 * @param $categoryId
	 *
	 * @return string
	 * @throws HttpException
	 */
	public function actionIndex($categoryId) {
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
	 * @param $categoryId
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionCreate($categoryId) {
		if (!($category = QuestionCategory::findOne(['id' => $categoryId]))) {
			throw new HttpException(404);
		}

		$model = new QuestionItemCreate($category);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionItem = $model->create())) {
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen létrehozva');
				return $this->redirect(Url::to('/admin/question-items/'.$categoryId));
			}
		}

		Yii::$app->view->params['pageClass'] = 'questionItemCreate';

		return $this->render('create', [
			'model' => $model,
			'category' => $category
		]);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionEdit($id) {
		if (!($questionItem = QuestionItem::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new QuestionItemEdit($questionItem->category);
		$model->loadQuestionItem($questionItem);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionItem = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen módosítva');
				return $this->redirect(Url::to('/admin/question-items/'.$questionItem->question_category_id));
			}
		}
		Yii::$app->view->params['pageClass'] = 'questionItemEdit';

		return $this->render('edit', [
			'model' => $model,
			'category' => $questionItem->category
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
		/** @var QuestionItem $questionItem */
		if (!($questionItem = QuestionItem::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$categoryId = $questionItem->question_category_id;
		$questionItem->delete();

		Yii::$app->session->setFlash('success', 'Kérdés sikeresen törölve');
		return $this->redirect(Url::to('/admin/question-items/'.$categoryId));
	}


}
