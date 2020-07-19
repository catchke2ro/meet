<?php

namespace app\controllers;

use app\lib\TreeLib;
use app\models\Badge;
use app\models\CommitmentCategory;
use app\models\CommitmentInstance;
use app\models\CommitmentItem;
use app\models\CommitmentOption;
use app\models\interfaces\FillInterface;
use app\models\QuestionCategory;
use app\models\User;
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
						'actions' => ['index', 'score', 'history'],
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
		if (($questionFillId = Yii::$app->request->get('qf'))) {
			$fill = UserQuestionFill::findOne(['id' => $questionFillId]);
			if (!$fill) {
				throw new HttpException(404);
			}
		} elseif ($user->hasCommitmentFill()) {
			$fill = $user->getLatestCommitmentFill();
		} else {
			return $this->redirect('/');
		}

		$checkedCommitmentOptions = $fill->getCheckedCommitmentOptions();

		$commitmentCategories = CommitmentCategory::find()->with(['items', 'items.options'])->orderBy('order ASC')->all();
		$questionCategories = QuestionCategory::find()->with(['items', 'items.options'])->orderBy('order ASC')->all();

		$categoriesByCommitments = $this->treeLib->populateTree($commitmentCategories);
		$this->treeLib->populateTree($questionCategories);


		$request = Yii::$app->request;
		if ($request->isPost) {
			try {
				$this->save($request, $categoriesByCommitments);
				Yii::$app->session->setFlash('success', 'Köszönjük a vállalásokat! Feldolgozzuk a beérkezett adatokat, és hamarosan visszajelzünk.');
			} catch (Exception $e) {
				Yii::$app->session->setFlash('error', 'Hiba történt a mentés során.');
			}
		}

		return $this->render('index', compact(
			'commitmentCategories',
			'fill',
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
					$instanceNumsToIds[$categoryId . '_' . $num] = $instance->id;
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

		/** @var CommitmentCategory[] $commitmentCategories */
		$specialPointCategoryIds = [];
		$commitmentCategories = CommitmentCategory::find()->with(['items', 'items.options'])->orderBy('order ASC')->all();
		foreach ($commitmentCategories as $commitmentCategory) {
			if ($commitmentCategory->special_points) {
				$specialPointCategoryIds[] = $commitmentCategory->id;
			}
		}
		$categoriesByCommitments = $this->treeLib->populateTree($commitmentCategories);

		/** @var Badge[] $badges */
		$badges = Badge::find()->orderBy('threshold ASC')->all();

		$postedOptions = $request->getBodyParam('options') ?: [];
		/** @var CommitmentOption[] $dbOptions */
		$dbOptions = CommitmentOption::find()->all();
		$dbOptionsByIds = [];
		foreach ($dbOptions as $dbOption) {
			$dbOptionsByIds[$dbOption->id] = $dbOption;
		}

		$score = $scoreWithSpecial = 0;
		foreach ($postedOptions as $commitmentId => $instances) {
			$categoryId = $categoriesByCommitments[$commitmentId] ?? null;
			foreach ($instances ?: [] as $instanceNumber => $optionId) {
				if (isset($dbOptionsByIds[$optionId])) {
					$scoreWithSpecial += (int) $dbOptionsByIds[$optionId]->score;
					if (!in_array($categoryId, $specialPointCategoryIds)) {
						$score += (int) $dbOptionsByIds[$optionId]->score;
					}
				}
			}
		}

		$currentBadge = $nextBadge = null;
		foreach ($badges as $badge) {
			if ($score >= $badge->threshold) {
				$currentBadge = $badge;
				$nextBadge = next($badges) ?: null;
				break;
			}
		}

		$nextBadgePercentage = null;
		if ($currentBadge && $nextBadge) {
			$nextBadgePercentage = round((($nextBadge->threshold - $currentBadge->threshold) / $score) * 100, 0);
		}


		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_JSON;
		$response->format = \yii\web\Response::FORMAT_JSON;
		$response->data = [
			'score'               => $score,
			'scoreWithSpecial'    => $scoreWithSpecial,
			'currentLevel'        => $currentBadge ? $currentBadge->name : null,
			'nextLevel'           => $nextBadge ? $nextBadge->name : null,
			'nextLevelPercentage' => $nextBadgePercentage
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

		/** @var UserCommitmentFill[] $fills */
		$fillIds = $user->getCommitmentFills()->orderBy('date DESC')->select('id')->column();

		$historyValues = UserCommitmentOption::find()
			->alias('commitmentOption')
			->innerJoinWith('option as option')
			->innerJoinWith('userCommitmentFill as fill')
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
