<?php

namespace app\controllers;

use app\lib\TreeLib;
use app\models\lutheran\Organization;
use app\models\QuestionCategory;
use app\models\QuestionInstance;
use app\models\OrgQuestionAnswer;
use app\models\OrgQuestionFill;
use DateTime;
use Exception;
use http\Exception\InvalidArgumentException;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;

/**
 * Class QuestionController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionController extends BaseController {

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
	 * Displays question form
	 *
	 * @return string
	 * @throws Exception
	 */
	public function actionIndex() {
		$user = Yii::$app->user->getIdentity();
		$questionCategories = QuestionCategory::find()
			->innerJoinWith(['orgTypes as orgTypes'])->andWhere(['orgTypes.org_type_id' => Yii::$app->user->getIdentity()->getOrgTypeId()])
			->with(['items', 'items.options'])
			->orderBy('order ASC')->all();

		$categoriesByQuestions = $this->treeLib->populateTree($questionCategories);

		$request = Yii::$app->request;
		if ($request->isPost) {
			$questionFillId = $this->save($request, $categoriesByQuestions);
			$this->redirect('/vallalasok?qf='.$questionFillId);
		}
		return $this->render('index', compact(
			'questionCategories',
			'user'
		));
	}


	/**
	 * Displays question form
	 *
	 * @param Request $request
	 *
	 * @param array   $categoriesByQuestions
	 *
	 * @return string
	 * @throws Exception
	 */
	protected function save(Request $request, array $categoriesByQuestions) {
		try {
			$transaction = Yii::$app->db->beginTransaction();

			/** @var User $user */
			/** @var Organization $organization */
			$user = Yii::$app->user->getIdentity();
			if (!($user && ($organization = $user->getOrganization()))) {
				throw new NotFoundHttpException();
			}
			$orgType = $request->getBodyParam('orgType');
			if (!($orgType && $orgType == $organization->orgType->id)) {
				throw new InvalidArgumentException('Invalid input parameters');
			}

			$fill = (new OrgQuestionFill());
			$fill->org_id = $organization->id;
			$fill->org_type = $orgType;
			$fill->date = (new DateTime())->format('Y-m-d H:i:s');
			$fill->save();

			$options = $request->getBodyParam('options') ?: [];
			$customInputs = $request->getBodyParam('customInputs') ?: [];
			$instanceNames = $request->getBodyParam('instanceNames') ?: [];

			$instanceNumsToIds = [];
			foreach ($instanceNames as $categoryId => $categoryInstances) {
				foreach ($categoryInstances as $num => $instanceName) {
					$instance = new QuestionInstance();
					$instance->name = $instanceName ?: $num;
					$instance->question_category_id = $categoryId;
					$instance->save();
					$instanceNumsToIds[$categoryId.'_'.$num] = $instance->id;
				}
			}
			foreach ($options as $questionId => $instances) {
				$categoryId = $categoriesByQuestions[$questionId] ?? null;
				foreach ($instances ?: [] as $instanceNumber => $optionId) {
					$answer = new OrgQuestionAnswer();
					$answer->org_question_fill_id = $fill->id;
					$answer->custom_input = $customInputs[$questionId][$optionId][$instanceNumber] ?: null;
					$answer->question_option_id = $optionId;
					if (isset($instanceNumsToIds[$categoryId.'_'.$instanceNumber])) {
						$answer->instance_id = $instanceNumsToIds[$categoryId.'_'.$instanceNumber];
					}
					$answer->save();
				}
			}
			$transaction->commit();
			return $fill->id;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}
