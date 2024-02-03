<?php

namespace app\modules\admin\controllers;

use app\models\CommitmentCategory;
use app\models\CommitmentItem;
use app\modules\admin\controllers\traits\ReorderTrait;
use app\modules\admin\models\forms\CommitmentItemCreate;
use app\modules\admin\models\forms\CommitmentItemEdit;
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
	 * @param int $categoryId
	 *
	 * @return Response|string
	 * @throws HttpException
	 */
	public function actionIndex(int $categoryId): Response|string {
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
	 * @param int $categoryId
	 *
	 * @return Response|string
	 * @throws HttpException
	 */
	public function actionCreate(int $categoryId): Response|string {
		if (!($category = CommitmentCategory::findOne(['id' => $categoryId]))) {
			throw new HttpException(404);
		}

		$model = new CommitmentItemCreate($category);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($commitmentItem = $model->create())) {
				$category->organizeOrders();
				Yii::$app->session->setFlash('success', 'Vállalás sikeresen létrehozva');

				if (!$this->request->isAjax) {
					return $this->redirect(Url::to('/admin/commitment-items?categoryId=' . $categoryId));
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
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionEdit(int $id): Response|string {
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
					return $this->redirect(Url::to('/admin/commitment-items?categoryId=' . $commitmentItem->commitmentCategoryId));
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
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 * @throws StaleObjectException
	 * @throws Throwable
	 */
	public function actionDelete(int $id): Response|string {
		/** @var CommitmentItem $commitmentItem */
		if (!($commitmentItem = CommitmentItem::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		if (!empty($commitmentItem->options)) {
			Yii::$app->session->setFlash('error', 'A vállaláshoz tartoznak opciók, előbb töröld azokat');

			return $this->redirect(Url::to('/admin/commitment-items?categoryId=' . $commitmentItem->commitmentCategoryId));
		}
		$categoryId = $commitmentItem->commitmentCategoryId;
		$commitmentItem->delete();

		Yii::$app->session->setFlash('success', 'Vállalás sikeresen törölve');

		return $this->redirect(Url::to('/admin/commitment-items?categoryId=' . $categoryId));
	}


	/**
	 * @param int    $id
	 * @param string $direction
	 *
	 * @throws HttpException
	 */
	public function actionReorder(int $id, string $direction): void {
		/** @var CommitmentItem $commitmentItem */
		if (!($commitmentItem = CommitmentItem::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}

		$category = $commitmentItem->category;

		$this->doReorder($commitmentItem, $category, 'items', $direction);
	}


}
