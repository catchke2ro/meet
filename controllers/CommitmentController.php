<?php

namespace app\controllers;

use app\lib\TreeLib;
use app\models\CommitmentCategory;
use app\models\CommitmentInstance;
use app\models\CommitmentOption;
use app\models\QuestionCategory;
use app\models\UserCommitmentAnswer;
use app\models\UserCommitmentFill;
use app\models\UserCommitmentOption;
use app\models\UserQuestionFill;
use DateTime;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Request;
use yii\web\Response;

/**
 * Class CommitmentController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentController extends Controller {

	/**
	 * @var TreeLib
	 */
	private $treeLib;


	/**
	 * CommitmentController constructor.
	 *
	 * @param         $id
	 * @param         $module
	 * @param TreeLib $treeLib
	 * @param array   $config
	 */
	public function __construct($id, $module, TreeLib $treeLib, $config = []) {
		parent::__construct($id, $module, $config);
		$this->treeLib = $treeLib;
	}


	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index', 'score'],
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
		$questionFill = null;
		if (($questionFillId = Yii::$app->request->get('qf'))) {
			$questionFill = UserQuestionFill::findOne(['id' => $questionFillId]);
			if (!$questionFill) {
				throw new HttpException(404);
			}
		}
		$commitmentCategories = CommitmentCategory::find()->with(['items', 'items.options'])->orderBy('order ASC')->all();
		$questionCategories = QuestionCategory::find()->with(['items', 'items.options'])->orderBy('order ASC')->all();

		$categoriesByCommitments = $this->treeLib->populateTree($commitmentCategories);
		$this->treeLib->populateTree($questionCategories);

		$checkedCommitmentOptions = $questionFill->getCheckedCommitmentOptions();

		$request = Yii::$app->request;
		if ($request->isPost) {
			$this->save($request, $categoriesByCommitments);
		}
		return $this->render('index', compact(
			'commitmentCategories',
			'questionFill',
			'checkedCommitmentOptions'
		));
	}


	/**
	 * Displays commitment form
	 *
	 * @param Request $request
	 *
	 * @param array   $categoriesByCommitments
	 *
	 * @return string
	 * @throws Exception
	 */
	protected function save(Request $request, array $categoriesByCommitments) {
		try {
			$transaction = Yii::$app->db->beginTransaction();
			$fill = (new UserCommitmentFill());
			$fill->user_id = 1;
			$fill->date = (new DateTime())->format('Y-m-d H:i:s');
			$fill->save();

			$options = $request->getBodyParam('options') ?: [];
			$customInputs = $request->getBodyParam('customInputs') ?: [];
			$instanceNames = $request->getBodyParam('instanceNames') ?: [];
			$intervals = $request->getBodyParam('intervals') ?: [];

			$instanceNumsToIds = [];
			foreach ($instanceNames as $categoryId => $categoryInstances) {
				foreach ($categoryInstances as $num => $instanceName) {
					$instance = new CommitmentInstance();
					$instance->name = $instanceName ?: $num;
					$instance->commitment_category_id = $categoryId;
					$instance->save();
					$instanceNumsToIds[$categoryId.'_'.$num] = $instance->id;
				}
			}
			foreach ($options as $commitmentId => $instances) {
				$categoryId = $categoriesByCommitments[$commitmentId] ?? null;
				foreach ($instances ?: [] as $instanceNumber => $optionId) {
					$fillOption = new UserCommitmentOption();
					$fillOption->user_commitment_fill_id = $fill->id;
					$fillOption->custom_input = $customInputs[$commitmentId][$optionId][$instanceNumber] ?: null;
					$fillOption->commitment_option_id = $optionId;
					$fillOption->months = $intervals[$commitmentId][$instanceNumber] ?? null;
					if (isset($instanceNumsToIds[$categoryId.'_'.$instanceNumber])) {
						$fillOption->instance_id = $instanceNumsToIds[$categoryId.'_'.$instanceNumber];
					}
					$fillOption->save();
				}
			}
			$transaction->commit();
			return $fill->id;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


	/**
	 */
	public function actionScore() {
		$request = Yii::$app->request;
		$postedOptions = $request->getBodyParam('options') ?: [];
		/** @var CommitmentOption[] $dbOptions */
		$dbOptions = CommitmentOption::find()->all();
		$dbOptionsByIds = [];
		foreach ($dbOptions as $dbOption) {
			$dbOptionsByIds[$dbOption->id] = $dbOption;
		}

		$score = 0;
		foreach ($postedOptions as $commitmentId => $instances) {
			foreach ($instances ?: [] as $instanceNumber => $optionId) {
				if (isset($dbOptionsByIds[$optionId])) {
					$score += (int) $dbOptionsByIds[$optionId]->score;
				}
			}
		}

		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_JSON;
		$response->data = ['score' => $score];
	}


}
