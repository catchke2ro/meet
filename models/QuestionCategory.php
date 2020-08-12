<?php

namespace app\models;

use app\lib\OrgTypes;
use app\models\interfaces\CategoryInterface;
use app\models\interfaces\DataTableModelInterface;
use app\models\traits\CategoryInstanceTrait;
use app\models\traits\WithItemsTrait;
use Symfony\Component\Console\Question\Question;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class QuestionCategory
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int                             $id
 * @property string                          $name
 * @property int                             $order
 * @property int                             $org_type_id
 * @property string                          $description
 * @property bool                            $has_instances
 * @property array|QuestionItem[]            items
 * @property QuestionOption                  $conditionOption
 * @property array|QuestionCategoryOrgType[] $orgTypes
 */
class QuestionCategory extends ActiveRecord implements CategoryInterface, DataTableModelInterface {

	use CategoryInstanceTrait;
	use WithItemsTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{question_categories}}';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getConditionOption() {
		return $this->hasOne(QuestionOption::class, ['id' => 'condition_question_option_id']);
	}


	/**
	 * @return ActiveQuery
	 */
	public function getOrgTypes() {
		return $this->hasMany(QuestionCategoryOrgType::class, ['question_category_id' => 'id']);
	}


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {

		$orgTypes = array_map(function (QuestionCategoryOrgType $orgType) {
			return OrgTypes::getInstance()[$orgType->org_type_id];
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
			'items'  => '<a href="/admin/question-items/' . $this->id . '" class="fa fa-list" title="Kérdések"></a>',
			'edit'   => '<a href="/admin/question-categories/edit/' . $this->id . '" class="fa fa-pencil" title="Szereksztés"></a>',
			'delete' => '<a href="/admin/question-categories/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
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
