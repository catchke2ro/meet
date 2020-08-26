<?php

namespace app\modules\meet\models;

use app\modules\meet\models\interfaces\DataTableModelInterface;
use DateTime;
use meetbase\models\UserCommitmentFill as BaseUserCommitmentFill;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserCommitmentFill
 *
 * @package   app\modules\meet\models
 * @author    SRG Group <dev@srg.hu>
 * @copyright 2020 SRG Group Kft.
 */
class UserCommitmentFill extends BaseUserCommitmentFill implements DataTableModelInterface {


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {
		return [
			'id'           => $this->id,
			'date'         => (new DateTime($this->date))->format('Y. m. d. H:i:s'),
			'user'         => $this->user->name,
			'targetModule' => $this->targetModule ? $this->targetModule->name : null,
			'score'        => $this->getScore()
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'view' => '<a href="/meet/user-commitments?id=' . $this->id . '" class="fa fa-eye" title="Megtekintés, szerkesztés"></a>',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getTextSearchColumns(): array {
		return [
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getOrderableColumns(): array {
		return [
			'id',
			'date',
			'user'
		];
	}


}
