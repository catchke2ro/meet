<?php

namespace app\modules\admin\controllers;

use app\models\Module;
use app\models\OrgCommitmentFill;
use app\modules\admin\models\forms\OrgCommitmentEdit;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class UserCommitmentsController
 *
 * User commitments fills
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class OrgCommitmentsController extends AbstractAdminController {


	/**
	 */
	public function actionIndex(): Response|string {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(OrgCommitmentFill::class, function (ActiveQuery $qb) {
				$qb->with(['options', 'options.commitmentOption']);
			});
		}

		return $this->render('index', []);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionView(int $id): Response|string {
		/** @var OrgCommitmentFill $orgCommitmentFill */
		$orgCommitmentFill = OrgCommitmentFill::find()->andWhere(['id' => $id])
			->with([
				'options',
				'options.commitmentOption',
				'options.commitmentOption.item',
				'options.commitmentOption.item.category'
			])
			->one();
		if (!($orgCommitmentFill)) {
			throw new HttpException(404);
		}
		Yii::$app->view->params['pageClass'] = 'OrgCommitmentFillView';

		$model = new OrgCommitmentEdit();
		$model->loadFill($orgCommitmentFill);
		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($savedFill = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Kitöltés sikeresen módosítva');

				return $this->redirect(Url::to('/admin/org-commitments/' . $savedFill->id));
			}
		}

		$options = $orgCommitmentFill->options;
		usort($options, function (\app\models\OrgCommitmentOption $a, \app\models\OrgCommitmentOption $b) {
			if ($a->commitmentOption->item->category->id === $b->commitmentOption->item->category->id) {
				return $a->commitmentOption->item->order < $b->commitmentOption->item->order ? - 1 : 1;
			} else {
				return $a->commitmentOption->item->category->order < $b->commitmentOption->item->category->order ? - 1 : 1;
			}
		});

		$modules = [];
		/** @var Module $module */
		foreach (Module::find()->orderBy('threshold ASC')->all() as $module) {
			$modules[$module->id] = $module->name;
		}

		return $this->render('view', [
			'fill'    => $orgCommitmentFill,
			'options' => $options,
			'modules' => $modules,
			'model'   => $model
		]);
	}


}
