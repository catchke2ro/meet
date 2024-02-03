<?php

namespace app\modules\admin\models\forms;

use app\models\QuestionCategory;
use app\models\QuestionItem;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class QuestionItemCreate
 *
 * QuestionItemCreate form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionItemCreate extends Model {

	public ?string $name = null;

	public ?int $order = null;

	public ?string $description = null;

	public ?QuestionCategory $category = null;

	public ?bool $isActive = null;


	/**
	 * QuestionItemCreate constructor.
	 *
	 * @param QuestionCategory $category
	 * @param array            $config
	 */
	public function __construct(QuestionCategory $category, array $config = []) {
		parent::__construct($config);
		$this->category = $category;
	}


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
		return [
			['name', 'trim'],
			['name', 'required'],
			['order', 'number'],
			['isActive', 'safe'],
			['description', 'safe'],
		];
	}


	/**
	 * @return QuestionItem|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function create(): ?QuestionItem {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$questionItem = new QuestionItem();
			$questionItem->name = $this->name;
			$questionItem->description = $this->description;
			$questionItem->order = $this->order;
			$questionItem->isActive = $this->isActive;
			$questionItem->questionCategoryId = $this->category->id;
			$success &= $questionItem->save();

			$transaction->commit();

			return $success ? $questionItem : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


	/**
	 * @return array
	 */
	public function attributeLabels(): array {
		return [
			'name'        => 'Név',
			'description' => 'Leírás',
			'order'       => 'Sorrend',
			'isActive'    => 'Aktív',
		];
	}


}
