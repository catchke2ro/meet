<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\CommitmentItem;
use app\models\CommitmentCategory;
use app\models\CommitmentOption;
use app\models\QuestionItem;
use app\models\QuestionCategory;
use app\models\QuestionOption;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class ImportController extends Controller {

	/**
	 * @var string
	 */
	protected $file;


	/**
	 * @param string $actionID
	 *
	 * @return array|string[]
	 */
	public function options($actionID) {
		return ['file'];
	}


	/**
	 * This command echoes what you have entered as the message.
	 *
	 * @return int Exit code
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
	 */
	public function actionExcel() {
		ini_set('display_errors', true);
		error_reporting(E_ALL);
		if (!($this->file && file_exists($this->file))) {
			$this->stderr("File option is missing or file doesn't exist\n");

			return ExitCode::DATAERR;
		}

		$spreadsheet = IOFactory::createReader('Xlsx')->load($this->file);
		[$commitmentCategories, $commitments] = $this->fetchCommitments($spreadsheet);

		$questionsSheet = $spreadsheet->getSheet(1);
		$questionCategories = $questions = $questionOptions = [];
		$commitmentOptions = [];
		$connections = [];
		foreach ($questionsSheet->getRowIterator() as $rI => $row) {
			if ($rI === 1) {
				continue;
			}
			$name = $questionCategoryId = $questionId = $optionId = $commitmentCategoryIds = $commitmentIds = $score = null;
			foreach ($row->getCellIterator() as $cI => $cell) {
				switch ($cI) {
					case 'A':
						$questionCategoryId = trim($cell->getValue());
						break;
					case 'B':
						$questionId = trim($cell->getValue());
						break;
					case 'C':
						$optionId = trim($cell->getValue());
						break;
					case 'D':
						$name = trim($cell->getValue());
						break;
					case 'E':
						$commitmentCategoryIds = trim($cell->getValue());
						break;
					case 'F':
						$commitmentIds = trim($cell->getValue());
						break;
					case 'G':
						$score = trim($cell->getValue());
						break;
				}
			}

			if ($name && $questionCategoryId && $questionId) {
				if (empty(trim($optionId))) {
					$questionCategories[] = $questionCategoryId;
					$questions[$questionCategoryId][$questionId] = $name;
				} else {
					$isCustomInput = trim($name) === 'Szabad beírás';
					$questionOptions[$questionCategoryId][$questionId][$optionId] = [
						'name'            => $name,
						'is_custom_input' => $isCustomInput
					];

					if ($commitmentCategoryIds && $commitmentIds) {
						$score = is_null($score) ? 0 : $score;

						//Categories can be multiline
						$commitmentCategoryIds = array_map(function ($id) {
							return trim($id);
						}, array_values(array_filter(explode("\n", $commitmentCategoryIds))));

						//Split by line first
						$commitmentIdLines = array_values(array_filter(explode("\n", $commitmentIds)));
						$commitmentIdLines = array_map(function ($commitmentIds) {
							return array_map(function ($id) {
								return trim($id);
							}, array_values(array_filter(explode(',', $commitmentIds))));
						}, $commitmentIdLines);

						foreach ($commitmentIdLines as $lineNum => $commitmentIds) {
							if (!isset($commitmentCategoryIds[$lineNum])) {
								//line not found under categories
								continue;
							}
							foreach ($commitmentIds as $commitmentId) {
								$commitmentOptions[$commitmentCategoryIds[$lineNum]][$commitmentId][$optionId] = [
									'name' => $name,
									'score' => $score,
									'is_custom_input' => $isCustomInput
								];
								$connections[] = [
									'question' => $questionCategoryId.'_'.$questionId.'_'.$optionId,
									'commitment' => $commitmentCategoryIds[$lineNum].'_'.$commitmentId.'_'.$optionId
								];
							}
						}
					}
				}
			}
		}

		$questionCategories = array_unique($questionCategories);

		$qcIdMap = $qIdMap = $qoIdMap = $ccIdMap = $cIdMap = $coIdMap = [];


		$i = 0;
		foreach ($questionCategories as $questionCategoryName) {
			$i++;
			$qcModel = (new QuestionCategory());
			$qcModel->setAttributes([
				'name' => $questionCategoryName,
				'order' => $i
			], false);
			$qcModel->save();
			$qcIdMap[$questionCategoryName] = $qcModel->id;
		}

		foreach ($questions as $questionCategoryId => $questionCategoryQuestions) {
			$i = 0;
			foreach ($questionCategoryQuestions as $id => $question) {
				$i++;
				$qModel = (new QuestionItem());
				$qModel->setAttributes([
					'name' => $question,
					'question_category_id' => $qcIdMap[$questionCategoryId],
					'order' => $i
				], false);
				$qModel->save();
				$qIdMap[$questionCategoryId.'_'.$id] = $qModel->id;
			}
		}

		foreach ($questionOptions as $questionCategoryId => $questionCategoryQuestions) {
			foreach ($questionCategoryQuestions as $questionId => $options) {
				$i = 0;
				foreach ($options as $id => $option) {
					$i++;
					$qoModel = (new QuestionOption());
					$qoModel->setAttributes([
						'name' => $option['name'],
						'question_category_id' => $qcIdMap[$questionCategoryId],
						'question_id' => $qIdMap[$questionCategoryId.'_'.$questionId],
						'order' => $i,
						'is_custom_input' => (int) $option['is_custom_input']
					], false);
					$qoModel->save();
					$qoIdMap[$questionCategoryId.'_'.$questionId.'_'.$id] = $qoModel->id;
				}
			}
		}


		$i = 0;
		foreach ($commitmentCategories as $id => $commitmentCategoryName) {
			$i++;
			$ccModel = (new CommitmentCategory());
			$ccModel->setAttributes([
				'name' => $commitmentCategoryName,
				'order' => $i
			], false);
			$ccModel->save();
			$ccIdMap[$id] = $ccModel->id;
		}

		foreach ($commitments as $commitmentCategoryId => $commitmentCategoryCommitments) {
			$i = 0;
			foreach ($commitmentCategoryCommitments as $id => $commitment) {
				$i++;
				$cModel = (new CommitmentItem());
				$cModel->setAttributes([
					'name' => $commitment,
					'commitment_category_id' => $ccIdMap[$commitmentCategoryId],
					'order' => $i
				], false);
				$cModel->save();
				$cIdMap[$commitmentCategoryId.'_'.$id] = $cModel->id;
			}
		}

		foreach ($commitmentOptions as $commitmentCategoryId => $commitmentCategoryCommitments) {
			foreach ($commitmentCategoryCommitments as $commitmentId => $options) {
				if (!isset($ccIdMap[$commitmentCategoryId]) || !isset($cIdMap[$commitmentCategoryId.'_'.$commitmentId])) {
					continue;
				}
				$i = 0;
				foreach ($options as $id => $option) {
					$i++;
					$coModel = (new CommitmentOption());
					$coModel->setAttributes([
						'name' => $option['name'],
						'score' => (int) $option['score'],
						'commitment_category_id' => $ccIdMap[$commitmentCategoryId],
						'commitment_id' => $cIdMap[$commitmentCategoryId.'_'.$commitmentId],
						'order' => $i,
						'is_custom_input' => (int) $option['is_custom_input'],
						'is_off_option' => false
					], false);
					$coModel->save();
					$coIdMap[$commitmentCategoryId.'_'.$commitmentId.'_'.$id] = $coModel->id;
				}
			}
		}

		foreach ($connections as $connection) {
			if (isset($qoIdMap[$connection['question']]) && isset($coIdMap[$connection['commitment']])) {
				Yii::$app->db->createCommand()->insert('commitments_by_questions', [
					'question_option_id' => $qoIdMap[$connection['question']],
					'commitment_option_id' => $coIdMap[$connection['commitment']]
				])->execute();
			}
		}

		echo '';
		return ExitCode::OK;
	}


	private function fetchCommitments(Spreadsheet $spreadsheet) {
		$commitmentsSheet = $spreadsheet->getSheet(2); //0 based
		$commitmentCategories = $commitments = [];
		foreach ($commitmentsSheet->getRowIterator() as $rI => $row) {
			if ($rI === 1) {
				continue;
			}
			$name = $categoryId = $commitmentId = null;
			foreach ($row->getCellIterator() as $cI => $cell) {
				switch ($cI) {
					case 'A':
						$categoryId = $cell->getValue();
						break;
					case 'B':
						$commitmentId = $cell->getValue();
						break;
					case 'C':
						$name = $cell->getValue();
						break;
				}
			}

			if ($name && $categoryId) {
				if (empty(trim($commitmentId))) {
					$commitmentCategories[$categoryId] = $name;
				} else {
					$commitments[$categoryId][$commitmentId] = $name;
				}
			}
		}

		return [
			$commitmentCategories,
			$commitments
		];
	}


}
