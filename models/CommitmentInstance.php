<?php

namespace app\models;

use app\models\traits\WithItemTrait;
use yii\db\ActiveQuery;

/**
 * Class CommitmentInstance
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int                 $id
 * @property string              $name
 * @property int                 $commitmentCategoryId
 * @property CommitmentCategory  $commitmentCategory
 * @property OrgCommitmentOption $orgCommitmentOptions
 */
class CommitmentInstance extends BaseModel {

	use WithItemTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'org_commitment_option_instances';
	}


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {
		return [
			'id'   => $this->id,
			'name' => $this->name
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'items'  => '<a href="/admin/commitment-options?itemId=' . $this->id . '" class="fa fa-list" title="Opciók"></a>',
			'edit'   => '<a href="/admin/commitment-items/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>',
			'delete' => '<a href="/admin/commitment-items/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
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
			'name'
		];
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrgCommitmentOptions(): ActiveQuery {
		return $this->hasMany(OrgCommitmentOption::class, ['instance_id' => 'id']);
	}


}
