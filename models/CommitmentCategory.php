<?php

namespace app\models;

use app\lib\OrgTypes;
use app\models\interfaces\CategoryInterface;
use app\models\interfaces\DataTableModelInterface;
use app\models\traits\CategoryInstanceTrait;
use app\models\traits\WithItemsTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class CommitmentCategory
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int                             $id
 * @property string                          $name
 * @property int                             $order
 * @property int                             $org_type_id
 * @property string                          $description
 * @property bool                            $has_instances
 * @property array|CommitmentItem[]          $items
 * @property CommitmentOption                $conditionOption
 * @property bool                            $special_points
 * @property int|null                        $question_category_inst_id
 * @property QuestionCategory                $questionCategoryInst
 * @property array|QuestionCategoryOrgType[] $orgTypes
 */
class CommitmentCategory extends ActiveRecord implements CategoryInterface, DataTableModelInterface {

	use CategoryInstanceTrait;
	use WithItemsTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{meet_commitment_categories}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getConditionOption() {
		return $this->hasOne(CommitmentOption::class, ['id' => 'condition_commitment_option_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getQuestionCategoryInst() {
		return $this->hasOne(QuestionCategory::class, ['question_category_inst_id' => 'id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrgTypes() {
		return $this->hasMany(CommitmentCategoryOrgType::class, ['commitment_category_id' => 'id']);
	}


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {

		$orgTypes = array_map(function (CommitmentCategoryOrgType $orgType) {
			return OrgTypes::getInstance()[$orgType->org_type_id];
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
			'items'  => '<a href="/admin/commitment-items/' . $this->id . '" class="fa fa-list" title="Vállalások"></a>',
			'edit'   => '<a href="/admin/commitment-categories/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>',
			'delete' => '<a href="/admin/commitment-categories/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
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
