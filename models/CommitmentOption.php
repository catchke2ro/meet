<?php

namespace app\models;

use app\models\interfaces\DataTableModelInterface;
use app\models\traits\WithItemTrait;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\web\Request;

/**
 * Class CommitmentOption
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 *
 * @property int              $id
 * @property string           $name
 * @property int              $order
 * @property bool             $isOffOption
 * @property bool             $isCustomInput
 * @property string           $description
 * @property int              $score
 * @property CommitmentItem   $item
 * @property int              $commitmentId
 * @property QuestionOption[] $questionOptions
 */
class CommitmentOption extends BaseModel implements DataTableModelInterface {

	use WithItemTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'commitment_options';
	}


	/**
	 * @return ActiveQuery
	 * @throws InvalidConfigException
	 */
	public function getQuestionOptions(): ActiveQuery {
		return $this->hasMany(QuestionOption::class, ['id' => 'question_option_id'])
			->viaTable('commitments_by_questions', ['commitment_option_id' => 'id']);
	}


	/**
	 * @param Request               $request
	 *
	 * @param OrgQuestionFill       $questionFill
	 * @param int                   $instanceNumber
	 * @param QuestionInstance|null $instance
	 *
	 * @return bool
	 */
	public function isChecked(Request $request, OrgQuestionFill $questionFill, int $instanceNumber, ?QuestionInstance $instance = null): bool {
		$checked = false;
		if ($this->item && $this->item->isOnlyCustomInput()) {
			$checked = true;
		}
		if ($request->isPost &&
			$this->item &&
			!empty($request->getBodyParam('options')) &&
			!empty($request->getBodyParam('options')[$this->item->id][$instanceNumber]) &&
			$request->getBodyParam('options')[$this->item->id][$instance] == $this->id
		) {
			$checked = true;
		} elseif (!empty($this->questionOptions)) {
			foreach ($this->questionOptions as $questionOption) {
				$questionFill;
			}

			return $checked;
		}

		return $checked;
	}


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {

		return [
			'id'            => $this->id,
			'name'          => $this->name,
			'description'   => $this->description,
			'order'         => $this->order,
			'isCustomInput' => $this->isCustomInput,
			'score'         => $this->score
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'edit'   => '<a href="/admin/commitment-options/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>',
			'delete' => '<a href="/admin/commitment-options/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
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


	/**
	 * @return array|array[]
	 */
	public static function getMultiselectOptions(): array {
		$commitmentOptions = self::find()
			->alias('commitmentOption')
			->innerJoinWith('item as commitmentItem')
			->innerJoinWith('item.category as commitmentCategory')
			->orderBy(['commitmentCategory.order' => 'ASC', 'commitmentItem.order' => 'ASC', 'commitmentOption.order' => 'ASC'])
			->all();

		$options = [];
		$optionsOptions = [];
		$prevCategory = $prevItem = null;
		/** @var CommitmentOption $commitmentOption */
		foreach ($commitmentOptions as $commitmentOption) {
			$item = $commitmentOption->item;
			$category = $commitmentOption->item->category;
			if ($category !== $prevCategory) {
				$options['category[' . $category->id . ']'] = $category->name;
				$optionsOptions['category[' . $category->id . ']'] = ['disabled' => true, 'style' => 'color: lightgray'];
			}
			if ($item !== $prevItem) {
				$options['item[' . $item->id . ']'] = str_repeat('&nbsp;', 4) . $item->name;
				$optionsOptions['item[' . $item->id . ']'] = ['disabled' => true, 'style' => 'color: lightgray'];
			}
			$options[$commitmentOption->id] = str_repeat('&nbsp;', 8) . $commitmentOption->name;
		}

		return [$options, $optionsOptions];
	}


}
