<?php

namespace app\controllers\admin;

use app\lib\OrgTypes;
use app\models\forms\admin\QuestionItemEdit;
use app\models\forms\admin\QuestionItemCreate;
use app\models\forms\admin\QuestionOptionCreate;
use app\models\forms\admin\QuestionOptionEdit;
use app\models\QuestionItem;
use app\models\QuestionOption;
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
 * Class QuestionOptionsController
 *
 * Question item CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionOptionsController extends AbstractAdminController {


	/**
	 * @param $itemId
	 *
	 * @return string
	 * @throws HttpException
	 */
	public function actionIndex($itemId) {
		if (!($item = QuestionItem::findOne(['id' => $itemId]))) {
			throw new HttpException(404);
		}

		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(QuestionOption::class, function (ActiveQuery $qb) use ($item) {
				$qb->andWhere(['question_id' => $item->id]);
			});
		}

		return $this->render('index', [
			'item' => $item
		]);
	}


	/**
	 * @param $itemId
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionCreate($itemId) {
		if (!($item = QuestionItem::findOne(['id' => $itemId]))) {
			throw new HttpException(404);
		}

		$model = new QuestionOptionCreate($item);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionOption = $model->create())) {
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen létrehozva');
				return $this->redirect(Url::to('/admin/question-options/'.$itemId));
			}
		}

		Yii::$app->view->params['pageClass'] = 'questionOptionCreate';

		return $this->render('create', [
			'model' => $model,
			'item' => $item
		]);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionEdit($id) {
		if (!($questionOption = QuestionOption::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new QuestionOptionEdit($questionOption->item);
		$model->loadQuestionOption($questionOption);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionOption = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen módosítva');
				return $this->redirect(Url::to('/admin/question-options/'.$questionOption->question_id));
			}
		}
		Yii::$app->view->params['pageClass'] = 'questionOptionEdit';

		return $this->render('edit', [
			'model' => $model,
			'item' => $questionOption->item
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
		/** @var QuestionOption $questionOption */
		if (!($questionOption = QuestionOption::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$itemId = $questionOption->question_id;
		$questionOption->delete();

		Yii::$app->session->setFlash('success', 'Kérdés sikeresen törölve');
		return $this->redirect(Url::to('/admin/question-options/'.$itemId));
	}


}
