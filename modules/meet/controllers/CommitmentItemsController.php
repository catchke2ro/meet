<?php

namespace app\modules\meet\controllers;

use app\modules\meet\controllers\traits\ReorderTrait;
use app\modules\meet\models\CommitmentCategory;
use app\modules\meet\models\CommitmentItem;
use app\modules\meet\models\forms\CommitmentItemCreate;
use app\modules\meet\models\forms\CommitmentItemEdit;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class CommitmentItemsController
 *
 * Commitment category CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentItemsController extends AbstractAdminController {

	use ReorderTrait;

	/**
	 * @param $categoryId
	 *
	 * @return string
	 * @throws HttpException
	 */
	public function actionIndex($categoryId) {
		if (!($category = CommitmentCategory::findOne(['id' => $categoryId]))) {
			throw new HttpException(404);
		}

		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(CommitmentItem::class, function (ActiveQuery $qb) use ($category) {
				$qb->andWhere(['commitment_category_id' => $category->id]);
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
		if (!($category = CommitmentCategory::findOne(['id' => $categoryId]))) {
			throw new HttpException(404);
		}

		$model = new CommitmentItemCreate($category);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($commitmentItem = $model->create())) {
				$category->organizeOrders();
				Yii::$app->session->setFlash('success', 'Vállalás sikeresen létrehozva');

				if (!$this->request->isAjax) {
					return $this->redirect(Url::to('/meet/commitment-items?categoryId=' . $categoryId));
				}
			} else {
				$this->response->setStatusCode(422);
			}
		}

		Yii::$app->view->params['pageClass'] = 'commitmentItemCreate';

		return $this->render('create', [
			'model'    => $model,
			'category' => $category
		]);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionEdit($id) {
		if (!($commitmentItem = CommitmentItem::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new CommitmentItemEdit($commitmentItem->category);
		$model->loadCommitmentItem($commitmentItem);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($commitmentItem = $model->edit())) {
				$commitmentItem->category->organizeOrders();
				Yii::$app->session->setFlash('success', 'Vállalás sikeresen módosítva');

				if (!$this->request->isAjax) {
					return $this->redirect(Url::to('/meet/commitment-items?categoryId=' . $commitmentItem->commitment_category_id));
				}
			} else {
				$this->response->setStatusCode(422);
			}
		}
		Yii::$app->view->params['pageClass'] = 'commitmentItemEdit';

		return $this->render('edit', [
			'model'    => $model,
			'category' => $commitmentItem->category
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
		/** @var CommitmentItem $commitmentItem */
		if (!($commitmentItem = CommitmentItem::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$categoryId = $commitmentItem->commitment_category_id;
		$commitmentItem->delete();

		Yii::$app->session->setFlash('success', 'Vállalás sikeresen törölve');

		return $this->redirect(Url::to('/meet/commitment-items?categoryId=' . $categoryId));
	}


	/**
	 * @param $id
	 * @param $direction
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionReorder($id, $direction) {
		/** @var CommitmentItem $commitmentItem */
		if (!($commitmentItem = CommitmentItem::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}

		$category = $commitmentItem->category;

		$this->doReorder($commitmentItem, $category, 'items', $direction);
	}


}
