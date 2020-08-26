<?php

namespace app\modules\meet\models\forms;

use app\modules\meet\models\UserCommitmentFill;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class UserCommitmentEdit
 *
 * ModuleCreate form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class UserCommitmentEdit extends Model {

	/**
	 * @var int
	 */
	public $manualScore;

	/**
	 * @var int
	 */
	public $manualModuleId;

	/**
	 * @var bool
	 */
	public $approved;

	/**
	 * @var string
	 */
	public $comment;

	/**
	 * @var UserCommitmentFill
	 */
	protected $fill;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['manualScore', 'safe'],
			['manualModuleId', 'safe'],
			['approved', 'safe'],
			['comment', 'safe'],
		];
	}


	/**
	 * @param UserCommitmentFill $fill
	 */
	public function loadFill(UserCommitmentFill $fill) {
		$this->fill = $fill;
		$this->manualScore = $fill->manual_score;
		$this->manualModuleId = $fill->manual_module_id;
		$this->approved = $fill->approved;
		$this->comment = $fill->comment;
	}


	/**
	 * Signs module up.
	 *
	 * @return UserCommitmentFill|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function edit(): ?UserCommitmentFill {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$this->fill->manual_score = $this->manualScore;
			$this->fill->manual_module_id = $this->manualModuleId;
			$this->fill->comment = $this->comment;
			$this->fill->approved = $this->approved;
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
	public function attributeLabels() {
		return [
			'manualScore'    => 'Egyedi pontszám',
			'manualModuleId' => 'Egyedi modul',
			'comment'        => 'Megjegyzés',
			'approved'       => 'Elfogadva',
		];
	}


}