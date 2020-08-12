<?php

namespace app\models\forms\admin;

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
 * @package app\models\forms\admin
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionItemCreate extends Model {

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var int
	 */
	public $order;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var QuestionCategory
	 */
	private $category;


	/**
	 * QuestionItemCreate constructor.
	 *
	 * @param QuestionCategory $category
	 * @param array            $config
	 */
	public function __construct(QuestionCategory $category, $config = []) {
		parent::__construct($config);
		$this->category = $category;
	}


	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['name', 'trim'],
			['name', 'required'],
			['order', 'number'],
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
			$questionItem->question_category_id = $this->category->id;
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
	public function attributeLabels() {
		return [
			'name'        => 'Név',
			'description' => 'Leírás',
			'order'       => 'Sorrend',
		];
	}


}