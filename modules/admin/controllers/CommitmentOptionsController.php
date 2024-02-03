<?php

namespace app\modules\admin\controllers;

use app\models\CommitmentItem;
use app\models\CommitmentOption;
use app\modules\admin\controllers\traits\ReorderTrait;
use app\modules\admin\models\forms\CommitmentOptionCreate;
use app\modules\admin\models\forms\CommitmentOptionEdit;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class CommitmentOptionsController
 *
 * Commitment item CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentOptionsController extends AbstractAdminController {

	use ReorderTrait;


	/**
	 * @param int $itemId
	 *
	 * @return Response|string
	 * @throws HttpException
	 */
	public function actionIndex(int $itemId): Response|string {
		if (!($item = CommitmentItem::findOne(['id' => $itemId]))) {
			throw new HttpException(404);
		}

		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(CommitmentOption::class, function (ActiveQuery $qb) use ($item) {
				$qb->andWhere(['commitment_id' => $item->id]);
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
		if (!($item = CommitmentItem::findOne(['id' => $itemId]))) {
			throw new HttpException(404);
		}

		$model = new CommitmentOptionCreate($item);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($commitmentOption = $model->create())) {
				$item->organizeOrders();
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen létrehozva');

				if (!$this->request->isAjax) {
					return $this->redirect(Url::to('/admin/commitment-options?itemId=' . $itemId));
				}
			} else {
				$this->response->setStatusCode(422);
			}
		}

		Yii::$app->view->params['pageClass'] = 'commitmentOptionCreate';

		return $this->render('create', [
			'model' => $model,
			'item'  => $item
		]);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionEdit(int $id): Response|string {
		if (!($commitmentOption = CommitmentOption::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new CommitmentOptionEdit($commitmentOption->item);
		$model->loadCommitmentOption($commitmentOption);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($commitmentOption = $model->edit())) {
				$commitmentOption->item->organizeOrders();
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen módosítva');

				if (!$this->request->isAjax) {
					return $this->redirect(Url::to('/admin/commitment-options?itemId=' . $commitmentOption->commitmentId));
				}
			} else {
				$this->response->setStatusCode(422);
			}
		}
		Yii::$app->view->params['pageClass'] = 'commitmentOptionEdit';

		return $this->render('edit', [
			'model' => $model,
			'item'  => $commitmentOption->item
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
		/** @var CommitmentOption $commitmentOption */
		if (!($commitmentOption = CommitmentOption::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$commitmentOption->unlinkAll('questionOptions', true);
		$itemId = $commitmentOption->commitmentId;
		$commitmentOption->delete();

		Yii::$app->session->setFlash('success', 'Kérdés sikeresen törölve');

		return $this->redirect(Url::to('/admin/commitment-options?itemId=' . $itemId));
	}


	/**
	 * @param int    $id
	 * @param string $direction
	 *
	 * @throws HttpException
	 */
	public function actionReorder(int $id, string $direction): void {
		/** @var CommitmentOption $commitmentOption */
		if (!($commitmentOption = CommitmentOption::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}

		$item = $commitmentOption->item;

		$this->doReorder($commitmentOption, $item, 'options', $direction);
	}


}
