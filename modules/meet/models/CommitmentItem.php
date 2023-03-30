<?php

namespace app\modules\meet\models;

use app\modules\meet\models\interfaces\DataTableModelInterface;
use meetbase\models\CommitmentItem as BaseCommitmentItem;

/**
 * Class CommitmentItem
 *
 * @package   app\modules\meet\models
 * @author    SRG Group <dev@srg.hu>
 * @copyright 2020 SRG Group Kft.
 */
class CommitmentItem extends BaseCommitmentItem implements DataTableModelInterface {


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {

		return [
			'id'          => $this->id,
			'name'        => $this->name,
			'description' => $this->description,
			'order'       => $this->order
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'items'  => '<a href="/meet/commitment-options?itemId=' . $this->id . '" class="fa fa-list" title="Opciók"></a>',
			'edit'   => '<a href="/meet/commitment-items/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>',
			'delete' => '<a href="/meet/commitment-items/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
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
