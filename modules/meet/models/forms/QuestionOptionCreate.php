<?php

namespace app\modules\meet\models\forms;

use app\modules\meet\models\QuestionCategory;
use app\modules\meet\models\QuestionItem;
use app\modules\meet\models\QuestionOption;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class QuestionOptionCreate
 *
 * QuestionOptionCreate form
 *
 * @package app\models\forms
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
	 * @var array
	 */
	public $commitmentOptions;


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
			['commitmentOptions', 'safe'],
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

			if (is_array($this->commitmentOptions)) {
				foreach ($this->commitmentOptions as $commitmentOptionId) {
					Yii::$app->db->createCommand()->insert(Yii::$app->params['table_prefix'].'commitments_by_questions', [
						'question_option_id' => $this->questionOption->id,
						'commitment_option_id' => $commitmentOptionId
					])->execute();
				}
			}

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