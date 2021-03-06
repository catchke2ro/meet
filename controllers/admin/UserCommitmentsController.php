<?php

namespace app\controllers\admin;

use app\lib\OrgTypes;
use app\models\forms\admin\CommitmentCategoryEdit;
use app\models\forms\admin\CommitmentCategoryCreate;
use app\models\CommitmentCategory;
use app\models\forms\admin\UserCommitmentEdit;
use app\models\Module;
use app\models\QuestionCategory;
use app\models\UserCommitmentFill;
use app\models\UserCommitmentOption;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
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
class UserCommitmentsController extends AbstractAdminController {


	/**
	 * @return string
	 */
	public function actionIndex() {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(UserCommitmentFill::class, function (ActiveQuery $qb) {
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
		/** @var UserCommitmentFill $userCommitmentFill */
		$userCommitmentFill = UserCommitmentFill::find()->andWhere(['id' => $id])
			->with([
				'options',
				'options.commitmentOption',
				'options.commitmentOption.item',
				'options.commitmentOption.item.category',
				'user'
			])
			->one();
		if (!($userCommitmentFill)) {
			throw new HttpException(404);
		}
		Yii::$app->view->params['pageClass'] = 'userCommitmentFillView';

		$model = new UserCommitmentEdit();
		$model->loadFill($userCommitmentFill);
		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($savedFill = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Kitöltés sikeresen módosítva');

				return $this->redirect(Url::to('/admin/user-commitments/' . $savedFill->id));
			}
		}

		$options = $userCommitmentFill->options;
		usort($options, function (UserCommitmentOption $a, UserCommitmentOption $b) {
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
			'fill'    => $userCommitmentFill,
			'options' => $options,
			'modules' => $modules,
			'model'   => $model
		]);
	}


}
