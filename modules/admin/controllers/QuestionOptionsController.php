<?php

namespace app\modules\admin\controllers;

use app\models\CommitmentOption;
use app\models\QuestionItem;
use app\models\QuestionOption;
use app\modules\admin\models\forms\QuestionOptionCreate;
use app\modules\admin\models\forms\QuestionOptionEdit;
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
	 * @param int $itemId
	 *
	 * @return Response|string
	 * @throws HttpException
	 */
	public function actionIndex(int $itemId): Response|string {
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
	 * @param int $itemId
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionCreate(int $itemId): Response|string {
		if (!($item = QuestionItem::findOne(['id' => $itemId]))) {
			throw new HttpException(404);
		}

		$model = new QuestionOptionCreate($item);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionOption = $model->create())) {
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen létrehozva');

				return $this->redirect(Url::to('/admin/question-options?itemId=' . $itemId));
			}
		}

		Yii::$app->view->params['pageClass'] = 'questionOptionCreate';

		[$commitmentOptions, $commitmentOptionsOptions] = CommitmentOption::getMultiselectOptions();

		return $this->render('create', [
			'model'                    => $model,
			'item'                     => $item,
			'commitmentOptions'        => $commitmentOptions,
			'commitmentOptionsOptions' => $commitmentOptionsOptions
		]);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionEdit(int $id): Response|string {
		if (!($questionOption = QuestionOption::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new QuestionOptionEdit($questionOption->item);
		$model->loadQuestionOption($questionOption);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($questionOption = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen módosítva');

				return $this->redirect(Url::to('/admin/question-options?itemId=' . $questionOption->questionId));
			}
		}
		Yii::$app->view->params['pageClass'] = 'questionOptionEdit';

		[$commitmentOptions, $commitmentOptionsOptions] = CommitmentOption::getMultiselectOptions();

		return $this->render('edit', [
			'model'                    => $model,
			'item'                     => $questionOption->item,
			'commitmentOptions'        => $commitmentOptions,
			'commitmentOptionsOptions' => $commitmentOptionsOptions
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
		/** @var QuestionOption $questionOption */
		if (!($questionOption = QuestionOption::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$questionOption->unlinkAll('commitmentOptions', true);
		$itemId = $questionOption->questionId;
		$questionOption->delete();

		Yii::$app->session->setFlash('success', 'Kérdés sikeresen törölve');

		return $this->redirect(Url::to('/admin/question-options?itemId=' . $itemId));
	}


}
