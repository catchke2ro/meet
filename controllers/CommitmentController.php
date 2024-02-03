<?php

namespace app\controllers;

use app\lib\TreeLib;
use app\models\CommitmentCategory;
use app\models\CommitmentInstance;
use app\models\CommitmentItem;
use app\models\CommitmentOption;
use app\models\interfaces\FillInterface;
use app\models\Module;
use app\models\Organization;
use app\models\OrgCommitmentFill;
use app\models\OrgCommitmentOption;
use app\models\OrgQuestionFill;
use app\models\QuestionCategory;
use app\models\User;
use DateTime;
use Exception;
use InvalidArgumentException;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;

/**
 * Class CommitmentController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentController extends BaseController {


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
	public function actions(): array {
		return [];
	}


	/**
	 * Displays commitment form
	 *
	 * @return Response|string
	 * @throws HttpException
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 * @throws \yii\db\Exception
	 */
	public function actionIndex(): Response|string {
		/** @var FillInterface $fill */
		$fill = null;
		/** @var User $user */
		$organization = null;
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
			'user',
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
	public function actionEnd(): string {
		return $this->render('end');
	}


	/**
	 * Displays commitment form
	 *
	 * @param Request $request
	 *
	 * @param array   $categoriesByCommitments
	 *
	 * @return int
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 * @throws \yii\db\Exception
	 */
	protected function save(Request $request, array $categoriesByCommitments): int {
		try {
			$transaction = Yii::$app->db->beginTransaction();

			$targetModuleId = $request->getBodyParam('targetModule');
			$targetModule = Module::findOne(['id' => $targetModuleId]) ?: Module::find()->orderBy('threshold ASC')->one();

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

			$fill = (new OrgCommitmentFill());
			$fill->orgId = $organization->id;
			$fill->targetModuleId = $targetModule->id;
			$fill->date = (new DateTime())->format('Y-m-d H:i:s');
			$fill->orgTypeId = $orgType;
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
					$instance->commitmentCategoryId = $categoryId;
					$instance->save();
					$instanceNumsToIds[$categoryId . '_' . $num] = $instance->id;
				}
			}
			foreach ($options as $commitmentId => $instances) {
				$categoryId = $categoriesByCommitments[$commitmentId] ?? null;
				foreach ($instances ?: [] as $instanceNumber => $optionId) {
					$fillOption = new OrgCommitmentOption();
					$fillOption->orgCommitmentFillId = $fill->id;
					$fillOption->customInput = $customInputs[$commitmentId][$optionId][$instanceNumber] ?? null;
					$fillOption->commitmentOptionId = $optionId;

					$fillOption->months = $intervals[$commitmentId][$instanceNumber] ?? null;
					if ($fillOption->months && isset($intervalMultipliers[$commitmentId][$instanceNumber])) {
						$fillOption->months *= $intervalMultipliers[$commitmentId][$instanceNumber];
					}
					if (isset($instanceNumsToIds[$categoryId . '_' . $instanceNumber])) {
						$fillOption->instanceId = $instanceNumsToIds[$categoryId . '_' . $instanceNumber];
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
	 * @return void
	 * @throws Throwable
	 */
	public function actionScore(): void {
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
		$response->format = Response::FORMAT_JSON;
		$response->data = [
			'score'                  => $score,
			'currentModule'          => $currentModule?->name,
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
	public function actionHistory($commitmentId): string {
		/** @var User $user */
		$user = Yii::$app->user->getIdentity();
		$this->layout = false;
		if (!($user && ($organization = $user->getOrganization()) && $organization->hasCommitmentFill() && ($commitment = CommitmentItem::findOne(['id' => $commitmentId])))) {
			return $this->render('historyempty');
		}

		/** @var OrgCommitmentFill[] $fills */
		$fillIds = $organization->getCommitmentFills()->orderBy('date DESC')->select('id')->column();

		$historyValues = OrgCommitmentOption::find()
			->alias('commitmentOption')
			->innerJoinWith('commitmentOption as option')
			->innerJoinWith('orgCommitmentFill as fill')
			->andWhere(['in', 'commitmentOption.org_commitment_fill_id', $fillIds])
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


		return $this->render('history', compact('historyRows', 'commitment'));
	}


}
