<?php

namespace app\controllers;

use app\lib\TreeLib;
use app\models\CommitmentCategory;
use app\models\CommitmentInstance;
use app\models\CommitmentItem;
use app\models\CommitmentOption;
use app\models\interfaces\FillInterface;
use app\models\lutheran\Organization;
use app\models\Module;
use app\models\QuestionCategory;
use app\models\lutheran\User;
use app\models\OrgCommitmentFill;
use app\models\OrgCommitmentOption;
use app\models\OrgQuestionFill;
use DateTime;
use Exception;
use http\Exception\InvalidArgumentException;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;

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
						'actions' => ['index', 'score', 'history', 'end'],
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
		/** @var FillInterface $fill */
		$fill = null;
		/** @var User $user */
		$user = Yii::$app->user->getIdentity();
		if (!($user && ($organization = $user->getOrganization()))) {
			throw new NotFoundHttpException();
		}
		if (($questionFillId = Yii::$app->request->get('qf'))) {
			$fill = OrgQuestionFill::findOne(['id' => $questionFillId]);
			if (!$fill) {
				throw new HttpException(404);
			}
		} elseif ($organization->hasCommitmentFill()) {
			$fill = $organization->getLatestCommitmentFill();
			if (!$fill->approved) {
				return $this->render('pendingfill', compact(
					'fill'
				));
			}
		}

		$checkedCommitmentOptions = $fill ? $fill->getCheckedCommitmentOptions() : [];

		$commitmentCategories = CommitmentCategory::find()
			->innerJoinWith(['orgTypes as orgTypes'])->andWhere(['orgTypes.org_type_id' => Yii::$app->user->getIdentity()->getOrgTypeId()])
			->with(['items', 'items.options'])->orderBy('order ASC')->all();
		$questionCategories = QuestionCategory::find()
			->innerJoinWith(['orgTypes as orgTypes'])->andWhere(['orgTypes.org_type_id' => Yii::$app->user->getIdentity()->getOrgTypeId()])
			->with(['items', 'items.options'])
			->orderBy('order ASC')->all();

		$categoriesByCommitments = $this->treeLib->populateTree($commitmentCategories);
		$this->treeLib->populateTree($questionCategories);


		$request = Yii::$app->request;
		if ($request->isPost) {
			try {
				$this->save($request, $categoriesByCommitments);
				return $this->redirect('/vallalasok/vege');
			} catch (Exception $e) {
				Yii::$app->session->setFlash('error', 'Hiba történt a mentés során.');
			}
		}

		$modules = Module::find()->orderBy('threshold ASC')->all();

		return $this->render('index', compact(
			'commitmentCategories',
			'fill',
			'modules',
			'checkedCommitmentOptions'
		));
	}


	/**
	 * Displays commitment form
	 *
	 * @return string
	 * @throws Exception
	 */
	public function actionEnd() {
		return $this->render('end');
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

			$targetModuleId = $request->getBodyParam('targetModule');
			$targetModule = Module::findOne(['id' => $targetModuleId]) ?: Module::find()->orderBy('threshold ASC')->one();

			/** @var \meetbase\models\lutheran\User $user */
			/** @var Organization $organization */
			$user = Yii::$app->user->getIdentity();
			if (!($user && ($organization = $user->getOrganization()))) {
				throw new NotFoundHttpException();
			}

			$orgType = $request->getBodyParam('orgType');
			if (!($orgType && $orgType == $organization->orgType->id)) {
				throw new InvalidArgumentException('Invalid input parameters');
			}

			$fill = (new OrgCommitmentFill());
			$fill->org_id = $organization->id;
			$fill->target_module_id = $targetModule->id;
			$fill->date = (new DateTime())->format('Y-m-d H:i:s');
			$fill->org_type = $orgType;
			$fill->save();

			$options = $request->getBodyParam('options') ?: [];
			$customInputs = $request->getBodyParam('customInputs') ?: [];
			$instanceNames = $request->getBodyParam('instanceNames') ?: [];
			$intervals = $request->getBodyParam('intervals') ?: [];
			$intervalMultipliers = $request->getBodyParam('intervalMultipliers') ?: [];



			$instanceNumsToIds = [];
			foreach ($instanceNames as $categoryId => $categoryInstances) {
				foreach ($categoryInstances as $num => $instanceName) {
					$instance = new CommitmentInstance();
					$instance->name = $instanceName ?: $num;
					$instance->commitment_category_id = $categoryId;
					$instance->save();
					$instanceNumsToIds[$categoryId . '_' . $num] = $instance->id;
				}
			}
			foreach ($options as $commitmentId => $instances) {
				$categoryId = $categoriesByCommitments[$commitmentId] ?? null;
				foreach ($instances ?: [] as $instanceNumber => $optionId) {
					$fillOption = new OrgCommitmentOption();
					$fillOption->org_commitment_fill_id = $fill->id;
					$fillOption->custom_input = $customInputs[$commitmentId][$optionId][$instanceNumber] ?: null;
					$fillOption->commitment_option_id = $optionId;

					$fillOption->months = $intervals[$commitmentId][$instanceNumber] ?? null;
					if ($fillOption->months && isset($intervalMultipliers[$commitmentId][$instanceNumber])) {
						$fillOption->months *= $intervalMultipliers[$commitmentId][$instanceNumber];
					}
					if (isset($instanceNumsToIds[$categoryId . '_' . $instanceNumber])) {
						$fillOption->instance_id = $instanceNumsToIds[$categoryId . '_' . $instanceNumber];
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

		$commitmentCategories = CommitmentCategory::find()
			->innerJoinWith(['orgTypes as orgTypes'])->andWhere(['orgTypes.org_type_id' => Yii::$app->user->getIdentity()->getOrgTypeId()])
			->with(['items', 'items.options'])->orderBy('order ASC')->all();
		$categoriesByCommitments = $this->treeLib->populateTree($commitmentCategories);

		/** @var Module[] $modules */
		$modules = Module::find()->orderBy('threshold ASC')->all();

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

		$targetModuleId = $request->getBodyParam('targetModule');
		$targetModulePercentage = null;
		if ($targetModuleId && ($targetModule = Module::findOne(['id' => $targetModuleId]))) {
			$targetModulePercentage = 100;
			if ($targetModule->threshold !== 0) {
				$targetModulePercentage = min(100, round(($score / $targetModule->threshold) * 100, 0));
			}
		}

		$currentModule = $nextModule = null;
		foreach ($modules as $module) {
			if ($score >= $module->threshold) {
				$currentModule = $module;
				$nextModule = next($modules) ?: null;
				break;
			}
		}

		$nextModulePercentage = null;
		if ($currentModule && $nextModule) {
			$nextModulePercentage = round((($nextModule->threshold - $currentModule->threshold) / $score) * 100, 0);
		}


		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_JSON;
		$response->format = \yii\web\Response::FORMAT_JSON;
		$response->data = [
			'score'                  => $score,
			'currentModule'          => $currentModule ? $currentModule->name : null,
			'nextModule'             => $nextModule ? $nextModule->name : null,
			'nextModulePercentage'   => $nextModulePercentage,
			'targetModulePercentage' => $targetModulePercentage
		];
	}


	/**
	 * @param $commitmentId
	 *
	 * @return string
	 * @throws HttpException
	 * @throws \Throwable
	 * @throws \yii\db\Exception
	 */
	public function actionHistory($commitmentId) {
		/** @var User $user */
		$user = Yii::$app->user->getIdentity();
		if (!($user && $user->hasCommitmentFill() && ($commitment = CommitmentItem::findOne(['id' => $commitmentId])))) {
			throw new HttpException(404);
		}

		/** @var OrgCommitmentFill[] $fills */
		$fillIds = $user->getCommitmentFills()->orderBy('date DESC')->select('id')->column();

		$historyValues = OrgCommitmentOption::find()
			->alias('commitmentOption')
			->innerJoinWith('commitmentOption as option')
			->innerJoinWith('orgCommitmentFill as fill')
			->andWhere(['in', 'commitmentOption.user_commitment_fill_id', $fillIds])
			->andWhere(['option.commitment_id' => $commitmentId])
			->orderBy('fill.date DESC')
			->select([
				'fill.date as fillDate',
				'commitmentOption.months as months',
				'commitmentOption.custom_input as customInputValue',
				'option.id as optionId',
				'option.name as optionName',
				'option.is_custom_input as optionIsCustomInput'
			])
			->createCommand()->queryAll();

		$historyRows = [];
		foreach ($historyValues as $value) {
			$historyRows[] = [
				'date'   => (new DateTime($value['fillDate']))->format('Y. m. d. H:i'),
				'name'   => !empty($value['optionIsCustomInput']) ? ($value['customInputValue'] ?? null) : ($value['optionName'] ?? null),
				'months' => $value['months'] ? reduceMonths($value['months']) : null
			];
		}

		$this->layout = false;

		return $this->render('history', compact('historyRows', 'commitment'));
	}


}
