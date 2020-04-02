<?php

namespace app\controllers;

use app\models\CommitmentCategory;
use app\models\UserCommitmentAnswer;
use app\models\UserCommitmentFill;
use DateTime;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Request;

/**
 * Class CommitmentController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentController extends Controller {


	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}


	/**
	 * {@inheritdoc}
	 */
	public function actions() {
		return [];
	}


	/**
	 * Displays commitment form
	 *
	 * @return string
	 * @throws Exception
	 */
	public function actionIndex() {
		$commitmentCategories = CommitmentCategory::find()
			->with(['commitments', 'commitments.commitmentOptions'])
			->orderBy('order ASC')->all();

		/** @var CommitmentCategory $commitmentCategory */
		foreach ($commitmentCategories as $commitmentCategory) {
			foreach ($commitmentCategory->commitments as $commitment) {
				$commitment->populateRelation('category', $commitmentCategory);
				foreach ($commitment->commitmentOptions as $commitmentOption) {
					$commitmentOption->populateRelation('commitment', $commitment);
				}
			}
		}
		$request = Yii::$app->request;
		if ($request->isPost) {
			$this->save($request);
		}
		return $this->render('index', compact(
			'commitmentCategories'
		));
	}


	/**
	 * Displays commitment form
	 *
	 * @param Request $request
	 *
	 * @return string
	 * @throws Exception
	 */
	protected function save(Request $request) {
		try {
			$transaction = Yii::$app->db->beginTransaction();
			$fill = (new UserCommitmentFill());
			$fill->user_id = 1;
			$fill->date = (new DateTime())->format('Y-m-d H:i:s');
			$fill->save();

			$options = $request->getBodyParam('options') ?: [];
			$customInputs = $request->getBodyParam('customInputs') ?: [];
			foreach ($options as $commitmentId => $commitmentOptions) {
				foreach ($commitmentOptions ?: [] as $optionId => $instances) {
					foreach ($instances ?: [] as $instanceNumber => $checked) {
						if ($checked) {
							$answer = new UserCommitmentAnswer();
							$answer->user_commitment_fill_id = $fill->id;
							$answer->instance_number = $instanceNumber;
							$answer->custom_input = $customInputs[$commitmentId][$optionId][$instanceNumber] ?: null;
							$answer->commitment_option_id = $optionId;
							$answer->save();
						}
					}
				}
			}
			$transaction->commit();
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}
