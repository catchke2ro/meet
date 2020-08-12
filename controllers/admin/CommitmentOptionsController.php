<?php

namespace app\controllers\admin;

use app\lib\OrgTypes;
use app\models\forms\admin\CommitmentItemEdit;
use app\models\forms\admin\CommitmentItemCreate;
use app\models\forms\admin\CommitmentOptionCreate;
use app\models\forms\admin\CommitmentOptionEdit;
use app\models\CommitmentItem;
use app\models\CommitmentOption;
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
 * Class CommitmentOptionsController
 *
 * Commitment item CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentOptionsController extends AbstractAdminController {


	/**
	 * @param $itemId
	 *
	 * @return string
	 * @throws HttpException
	 */
	public function actionIndex($itemId) {
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
	 * @param $itemId
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionCreate($itemId) {
		if (!($item = CommitmentItem::findOne(['id' => $itemId]))) {
			throw new HttpException(404);
		}

		$model = new CommitmentOptionCreate($item);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($commitmentOption = $model->create())) {
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen létrehozva');
				return $this->redirect(Url::to('/admin/commitment-options/'.$itemId));
			}
		}

		Yii::$app->view->params['pageClass'] = 'commitmentOptionCreate';

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
		if (!($commitmentOption = CommitmentOption::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new CommitmentOptionEdit($commitmentOption->item);
		$model->loadCommitmentOption($commitmentOption);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($commitmentOption = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Kérdés sikeresen módosítva');
				return $this->redirect(Url::to('/admin/commitment-options/'.$commitmentOption->commitment_id));
			}
		}
		Yii::$app->view->params['pageClass'] = 'commitmentOptionEdit';

		return $this->render('edit', [
			'model' => $model,
			'item' => $commitmentOption->item
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
		/** @var CommitmentOption $commitmentOption */
		if (!($commitmentOption = CommitmentOption::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$itemId = $commitmentOption->commitment_id;
		$commitmentOption->delete();

		Yii::$app->session->setFlash('success', 'Kérdés sikeresen törölve');
		return $this->redirect(Url::to('/admin/commitment-options/'.$itemId));
	}


}
