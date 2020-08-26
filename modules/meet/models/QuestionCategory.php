<?php

namespace app\modules\meet\models;

use app\modules\meet\models\interfaces\DataTableModelInterface;
use meetbase\models\QuestionCategory as BaseQuestionCategory;

/**
 * Class QuestionCategory
 *
 * @package   app\modules\meet\models
 * @author    SRG Group <dev@srg.hu>
 * @copyright 2020 SRG Group Kft.
 */
class QuestionCategory extends BaseQuestionCategory implements DataTableModelInterface {


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {

		$orgTypes = array_map(function (\meetbase\models\QuestionCategoryOrgType $orgType) {
			return OrganizationType::getList()[$orgType->org_type_id] ?? null;
		}, $this->orgTypes ?: []);

		return [
			'id'           => $this->id,
			'name'         => $this->name,
			'description'  => $this->description,
			'order'        => $this->order,
			'orgTypes'     => implode(',', $orgTypes),
			'hasInstances' => $this->has_instances
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'items'  => '<a href="/meet/question-items?categoryId=' . $this->id . '" class="fa fa-list" title="Kérdések"></a>',
			'edit'   => '<a href="/meet/question-categories/edit?id=' . $this->id . '" class="fa fa-pencil" title="Szereksztés"></a>',
			'delete' => '<a href="/meet/question-categories/delete?id=' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
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
