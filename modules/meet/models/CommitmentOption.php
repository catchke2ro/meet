<?php

namespace app\modules\meet\models;

use app\modules\meet\models\interfaces\DataTableModelInterface;
use meetbase\models\CommitmentOption as BaseCommitmentOption;

/**
 * Class CommitmentOption
 *
 * @package   app\modules\meet\models
 * @author    SRG Group <dev@srg.hu>
 * @copyright 2020 SRG Group Kft.
 */
class CommitmentOption extends BaseCommitmentOption implements DataTableModelInterface {

	/**
	 * @return array
	 */
	public function toDataTableArray(): array {

		return [
			'id'            => $this->id,
			'name'          => $this->name,
			'description'   => $this->description,
			'order'         => $this->order,
			'isCustomInput' => $this->is_custom_input,
			'score'         => $this->score
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'edit'   => '<a href="/meet/commitment-options/edit?id=' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>',
			'delete' => '<a href="/meet/commitment-options/delete?id=' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getTextSearchColumns(): array {
		return [
			'name',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getOrderableColumns(): array {
		return [
			'name',
			'order'
		];
	}


}
