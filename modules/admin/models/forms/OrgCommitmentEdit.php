<?php

namespace app\modules\admin\models\forms;

use app\models\OrgCommitmentFill;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class OrgCommitmentEdit
 *
 * ModuleCreate form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class OrgCommitmentEdit extends Model {

	public ?int $manualScore = null;

	public ?int $manualModuleId = null;

	public ?bool $approved = null;

	public ?string $comment = null;

	protected ?OrgCommitmentFill $fill = null;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
		return [
			['manualScore', 'safe'],
			['manualModuleId', 'safe'],
			['approved', 'safe'],
			['comment', 'safe'],
		];
	}


	/**
	 * @param OrgCommitmentFill $fill
	 */
	public function loadFill(OrgCommitmentFill $fill): void {
		$this->fill = $fill;
		$this->manualScore = $fill->manualScore;
		$this->manualModuleId = $fill->manualModuleId;
		$this->approved = $fill->isApproved;
		$this->comment = $fill->comment;
	}


	/**
	 * Signs module up.
	 *
	 * @return OrgCommitmentFill|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function edit(): ?OrgCommitmentFill {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$this->fill->manualScore = $this->manualScore;
			$this->fill->manualModuleId = $this->manualModuleId;
			$this->fill->isApproved = $this->approved;
			$this->fill->comment = $this->comment;
			$success &= $this->fill->save();

			$transaction->commit();

			return $success ? $this->fill : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


	/**
	 * @return array
	 */
	public function attributeLabels(): array {
		return [
			'manualScore'    => 'Egyedi pontszám',
			'manualModuleId' => 'Egyedi modul',
			'comment'        => 'Megjegyzés',
			'approved'       => 'Elfogadva',
		];
	}


}
