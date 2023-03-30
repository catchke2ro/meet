<?php

namespace app\modules\meet\models;

use app\modules\meet\models\interfaces\DataTableModelInterface;
use meetbase\models\CommitmentCategory as BaseCommitmentCategory;

/**
 * Class CommitmentCategory
 *
 * @package   app\modules\meet\models
 * @author    SRG Group <dev@srg.hu>
 * @copyright 2020 SRG Group Kft.
 */
class CommitmentCategory extends BaseCommitmentCategory implements DataTableModelInterface {


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {

		$orgTypes = array_map(function (\meetbase\models\CommitmentCategoryOrgType $orgType) {
			return OrganizationType::getList()[$orgType->org_type_id] ?? null;
		}, $this->orgTypes ?: []);

		return [
			'id'                     => $this->id,
			'name'                   => $this->name,
			'description'            => $this->description,
			'order'                  => $this->order,
			'orgTypes'               => implode(',', $orgTypes),
			'hasInstances'           => $this->has_instances,
			'questionCategoryInstId' => $this->question_category_inst_id
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'items'  => '<a href="/meet/commitment-items?categoryId=' . $this->id . '" class="fa fa-list" title="Vállalások"></a>',
			'edit'   => '<a href="/meet/commitment-categories/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>',
			'delete' => '<a href="/meet/commitment-categories/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
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
