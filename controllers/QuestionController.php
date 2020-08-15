<?php

namespace app\controllers;

use app\lib\OrgTypes;
use app\lib\TreeLib;
use app\models\QuestionCategory;
use app\models\QuestionInstance;
use app\models\UserQuestionAnswer;
use app\models\UserQuestionFill;
use DateTime;
use Exception;
use http\Exception\InvalidArgumentException;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Request;

/**
 * Class QuestionController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionController extends Controller {

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
			'questionCategories'
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

			$orgType = $request->getBodyParam('orgType');
			if (!($orgType && OrgTypes::getInstance()->offsetExists($orgType))) {
				throw new InvalidArgumentException('Invalid input parameters');
			}

			$fill = (new UserQuestionFill());
			$fill->user_id = 1;
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
					$answer = new UserQuestionAnswer();
					$answer->user_question_fill_id = $fill->id;
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
