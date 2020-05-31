<?php

namespace app\controllers;

use app\models\QuestionCategory;
use app\models\QuestionInstance;
use app\models\UserQuestionAnswer;
use app\models\UserQuestionFill;
use DateTime;
use Exception;
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
			->with(['items', 'items.options'])
			->orderBy('order ASC')->all();

		$categoriesByQuestions = [];
		/** @var QuestionCategory $questionCategory */
		foreach ($questionCategories as $questionCategory) {
			foreach ($questionCategory->items as $question) {
				$categoriesByQuestions[$question->id] = $questionCategory->id;
				$question->populateRelation('category', $questionCategory);
				foreach ($question->options as $questionOption) {
					$questionOption->populateRelation('question', $question);
				}
			}
		}
		$request = Yii::$app->request;
		if ($request->isPost) {
			$this->save($request, $categoriesByQuestions);
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
			$fill = (new UserQuestionFill());
			$fill->user_id = 1;
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
			foreach ($options as $questionId => $questionOptions) {
				foreach ($questionOptions ?: [] as $optionId => $instances) {
					$categoryId = $categoriesByQuestions[$questionId] ?? null;
					foreach ($instances ?: [] as $instanceNumber => $checked) {
						if ($checked) {
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
				}
			}
			$transaction->commit();
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}
