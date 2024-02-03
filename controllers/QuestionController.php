<?php

namespace app\controllers;

use app\lib\TreeLib;
use app\models\Organization;
use app\models\OrgQuestionAnswer;
use app\models\OrgQuestionFill;
use app\models\QuestionCategory;
use app\models\QuestionInstance;
use app\models\User;
use DateTime;
use Exception;
use InvalidArgumentException;
use Throwable;
use Yii;
use yii\filters\AccessControl;
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
	 * CommitmentController constructor.
	 *
	 * @param         $id
	 * @param         $module
	 * @param TreeLib $treeLib
	 * @param array   $config
	 */
	public function __construct($id, $module, protected TreeLib $treeLib, array $config = []) {
		parent::__construct($id, $module, $config);
	}


	/**
	 * {@inheritdoc}
	 */
	public function behaviors(): array {
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => ['index'],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
		];
	}


	/**
	 * {@inheritdoc}
	 */
	public function actions(): array {
		return [];
	}


	/**
	 * Displays question form
	 *
	 * @return string
	 * @throws Throwable
	 */
	public function actionIndex(): string {
		$user = Yii::$app->user->getIdentity();
		$questionCategories = QuestionCategory::find()
			->innerJoinWith(['orgTypes as orgTypes'])->andWhere(['orgTypes.org_type_id' => Yii::$app->user->getIdentity()->getOrgTypeId()])
			->with(['items', 'items.options'])
			->orderBy('order ASC')->all();

		$categoriesByQuestions = $this->treeLib->populateTree($questionCategories);

		$request = Yii::$app->request;
		if ($request->isPost) {
			$questionFillId = $this->save($request, $categoriesByQuestions);
			$this->redirect('/vallalasok?qf=' . $questionFillId);
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
	 * @return int
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 * @throws \yii\db\Exception
	 */
	protected function save(Request $request, array $categoriesByQuestions): int {
		try {
			$transaction = Yii::$app->db->beginTransaction();

			/** @var User $user */
			/** @var Organization $organization */
			$user = Yii::$app->user->getIdentity();
			if (!($user && ($organization = $user->getOrganization()))) {
				throw new NotFoundHttpException();
			}
			$orgType = $request->getBodyParam('orgType');
			if (!($orgType && $orgType == $organization->organizationTypeId)) {
				throw new InvalidArgumentException('Invalid input parameters');
			}

			$fill = (new OrgQuestionFill());
			$fill->orgId = $organization->id;
			$fill->orgTypeId = $orgType;
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
					$instance->questionCategoryId = $categoryId;
					$instance->save();
					$instanceNumsToIds[$categoryId . '_' . $num] = $instance->id;
				}
			}
			foreach ($options as $questionId => $instances) {
				$categoryId = $categoriesByQuestions[$questionId] ?? null;
				foreach ($instances ?: [] as $instanceNumber => $optionId) {
					$answer = new OrgQuestionAnswer();
					$answer->orgQuestionFillId = $fill->id;
					$answer->customInput = $customInputs[$questionId][$optionId][$instanceNumber] ?? null;
					$answer->questionOptionId = $optionId;
					if (isset($instanceNumsToIds[$categoryId . '_' . $instanceNumber])) {
						$answer->instanceId = $instanceNumsToIds[$categoryId . '_' . $instanceNumber];
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
