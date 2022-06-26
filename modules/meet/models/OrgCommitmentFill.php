<?php

namespace app\modules\meet\models;

use app\modules\meet\models\interfaces\DataTableModelInterface;
use DateTime;
use meetbase\models\OrgCommitmentFill as BaseOrgCommitmentFill;

/**
 * Class OrgCommitmentFill
 *
 * @package   app\modules\meet\models
 * @author    SRG Group <dev@srg.hu>
 * @copyright 2020 SRG Group Kft.
 */
class OrgCommitmentFill extends BaseOrgCommitmentFill implements DataTableModelInterface {


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {
		return [
			'id'           => $this->id,
			'date'         => (new DateTime($this->date))->format('Y. m. d. H:i:s'),
			'user'         => $this->organization->nev,
			'targetModule' => $this->targetModule?->name,
			'score'        => $this->getScore()
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'view' => '<a href="/meet/org-commitments/view?id=' . $this->id . '" class="fa fa-eye" title="Megtekintés, szerkesztés"></a>',
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
