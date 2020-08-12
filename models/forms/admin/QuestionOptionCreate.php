<?php

namespace app\models\forms\admin;

use app\models\QuestionCategory;
use app\models\QuestionItem;
use app\models\QuestionOption;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class QuestionOptionCreate
 *
 * QuestionOptionCreate form
 *
 * @package app\models\forms\admin
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionOptionCreate extends Model {

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
	 * @var bool
	 */
	public $isCustomInput;

	/**
	 * @var QuestionItem
	 */
	private $item;


	/**
	 * QuestionOptionCreate constructor.
	 *
	 * @param QuestionItem $item
	 * @param array        $config
	 */
	public function __construct(QuestionItem $item, $config = []) {
		parent::__construct($config);
		$this->item = $item;
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
			['isCustomInput', 'safe'],
		];
	}


	/**
	 * @return QuestionOption|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function create(): ?QuestionOption {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$questionOption = new QuestionOption();
			$questionOption->name = $this->name;
			$questionOption->description = $this->description;
			$questionOption->order = $this->order;
			$questionOption->is_custom_input = $this->isCustomInput;
			$questionOption->question_id = $this->item->id;
			$success &= $questionOption->save();

			$transaction->commit();

			return $success ? $questionOption : null;
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
			'name'          => 'Név',
			'description'   => 'Leírás',
			'order'         => 'Sorrend',
			'isCustomInput' => 'Egyedi szöveges válasz',
		];
	}


}