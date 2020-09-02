<?php

namespace app\modules\meet\controllers;

use app\modules\meet\models\CommitmentOption;
use app\modules\meet\models\forms\QuestionOptionCreate;
use app\modules\meet\models\forms\QuestionOptionEdit;
use app\modules\meet\models\QuestionItem;
use app\modules\meet\models\QuestionOption;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
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

				return $this->redirect(Url::to('/meet/question-options?itemId=' . $itemId));
			}
		}

		Yii::$app->view->params['pageClass'] = 'questionOptionCreate';

		list($commitmentOptions, $commitmentOptionsOptions) = CommitmentOption::getMultiselectOptions();

		return $this->render('create', [
			'model' => $model,
			'item'  => $item,
			'commitmentOptions' => $commitmentOptions,
			'commitmentOptionsOptions' => $commitmentOptionsOptions
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

				return $this->redirect(Url::to('/meet/question-options?itemId=' . $questionOption->question_id));
			}
		}
		Yii::$app->view->params['pageClass'] = 'questionOptionEdit';

		list($commitmentOptions, $commitmentOptionsOptions) = CommitmentOption::getMultiselectOptions();

		return $this->render('edit', [
			'model' => $model,
			'item'  => $questionOption->item,
			'commitmentOptions' => $commitmentOptions,
			'commitmentOptionsOptions' => $commitmentOptionsOptions
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

		return $this->redirect(Url::to('/meet/question-options?itemId=' . $itemId));
	}


}