<?php

namespace app\modules\meet\controllers;

use app\modules\meet\models\forms\OrgCommitmentEdit;
use app\modules\meet\models\Module;
use app\modules\meet\models\OrgCommitmentFill;
use Exception;
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
	 * @return string
	 */
	public function actionIndex() {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(OrgCommitmentFill::class, function (ActiveQuery $qb) {
				$qb->with(['options', 'options.commitmentOption']);
			});
		}

		return $this->render('index', []);
	}


	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionView($id) {
		/** @var OrgCommitmentFill $OrgCommitmentFill */
		$OrgCommitmentFill = OrgCommitmentFill::find()->andWhere(['id' => $id])
			->with([
				'options',
				'options.commitmentOption',
				'options.commitmentOption.item',
				'options.commitmentOption.item.category'
			])
			->one();
		if (!($OrgCommitmentFill)) {
			throw new HttpException(404);
		}
		Yii::$app->view->params['pageClass'] = 'OrgCommitmentFillView';

		$model = new OrgCommitmentEdit();
		$model->loadFill($OrgCommitmentFill);
		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($savedFill = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Kitöltés sikeresen módosítva');

				return $this->redirect(Url::to('/meet/org-commitments?id=' . $savedFill->id));
			}
		}

		$options = $OrgCommitmentFill->options;
		usort($options, function (\meetbase\models\OrgCommitmentOption $a, \meetbase\models\OrgCommitmentOption $b) {
			if ($a->commitmentOption->item->category->id === $b->commitmentOption->item->category->id) {
				return $a->commitmentOption->item->order < $b->commitmentOption->item->order ? - 1 : 1;
			} else {
				return $a->commitmentOption->item->category->order < $b->commitmentOption->item->category->order ? - 1 : 1;
			}
		});

		$modules = [];
		foreach (Module::find()->orderBy('threshold ASC')->all() as $module) {
			$modules[$module->id] = $module->name;
		}

		return $this->render('view', [
			'fill'    => $OrgCommitmentFill,
			'options' => $options,
			'modules' => $modules,
			'model'   => $model
		]);
	}


}
