<?php

namespace app\modules\admin\controllers;

use app\models\Organization;
use app\models\OrganizationType;
use app\modules\admin\models\forms\OrganizationEdit;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class OrganizationController
 *
 * Organization CRUD
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class OrganizationsController extends AbstractAdminController {


	/**
	 * @return Response|string
	 */
	public function actionIndex(): Response|string {
		if (Yii::$app->request->isAjax) {
			return $this->handlaDTAjax(Organization::class);
		}

		return $this->render('index', []);
	}


	/**
	 * @param int $id
	 *
	 * @return string|Response
	 * @throws HttpException
	 */
	public function actionEdit(int $id): Response|string {
		if (!($organization = Organization::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$model = new OrganizationEdit();
		$model->loadOrganization($organization);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			if (($organization = $model->edit())) {
				Yii::$app->session->setFlash('success', 'Szervezet sikeresen módosítva');

				return $this->redirect(Url::to('/admin/organizations'));
			} else {
				Yii::$app->session->setFlash('error', 'Hiba történt a szervezet módosítása közben');
			}
		}
		Yii::$app->view->params['pageClass'] = 'organizationEdit';

		return $this->render('edit', [
			'orgTypes'     => OrganizationType::getList(),
			'model'        => $model
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
		/** @var Organization $organization */
		if (!($organization = Organization::findOne(['id' => $id]))) {
			throw new HttpException(404);
		}
		$organization->delete();

		Yii::$app->session->setFlash('success', 'Szervezet sikeresen törölve');

		return $this->redirect(Url::to('/admin/organizations'));
	}


}
