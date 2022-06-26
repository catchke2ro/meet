<?php

namespace app\modules\meet\models\forms;

use app\models\lutheran\Event;
use app\modules\meet\models\OrgCommitmentFill;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class OrgCommitmentEdit
 *
 * ModuleCreate form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class OrgCommitmentEdit extends Model {

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
	 * @var OrgCommitmentFill
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
	 * @param OrgCommitmentFill $fill
	 */
	public function loadFill(OrgCommitmentFill $fill) {
		$this->fill = $fill;
		$this->manualScore = $fill->manual_score;
		$this->manualModuleId = $fill->manual_module_id;
		$this->approved = $fill->isApproved();
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

			$this->fill->manual_score = $this->manualScore;
			$this->fill->manual_module_id = $this->manualModuleId;
			$this->fill->comment = $this->comment;
			$regApprovedEvent = Event::createCommitmentApprovedEvent($this->fill->organization, $this->fill);
			$regApprovedEvent->save();
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