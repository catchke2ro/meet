<?php

namespace app\controllers\admin;

use app\lib\OrgTypes;
use app\models\forms\admin\CommitmentCategoryEdit;
use app\models\forms\admin\CommitmentCategoryCreate;
use app\models\forms\admin\CommitmentItemCreate;
use app\models\forms\admin\CommitmentItemEdit;
use app\models\CommitmentCategory;
use app\models\CommitmentItem;
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
 * Class CommitmentItemsController
 *
 * Commitment category CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentItemsController extends AbstractAdminController {


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
				Yii::$app->session->setFlash('success', 'Vállalás sikeresen létrehozva');
				return $this->redirect(Url::to('/admin/commitment-items/'.$categoryId));
			}
		}

		Yii::$app->view->params['pageClass'] = 'commitmentItemCreate';

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
		if (!($commitmentItem = CommitmentItem::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new CommitmentItemEdit($commitmentItem->category);
		$model->loadCommitmentItem($commitmentItem);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($commitmentItem = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Vállalás sikeresen módosítva');
				return $this->redirect(Url::to('/admin/commitment-items/'.$commitmentItem->commitment_category_id));
			}
		}
		Yii::$app->view->params['pageClass'] = 'commitmentItemEdit';

		return $this->render('edit', [
			'model' => $model,
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
		return $this->redirect(Url::to('/admin/commitment-items/'.$categoryId));
	}


}
