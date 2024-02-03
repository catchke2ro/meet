<?php

namespace app\modules\admin\models\forms;

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
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionOptionCreate extends Model {

	public ?string $name = null;

	public ?int $order = null;

	public ?string $description = null;

	public ?bool $isCustomInput = null;

	public ?QuestionItem $item = null;

	public ?array $commitmentOptions = null;


	/**
	 * QuestionOptionCreate constructor.
	 *
	 * @param QuestionItem $item
	 * @param array        $config
	 */
	public function __construct(QuestionItem $item, array $config = []) {
		parent::__construct($config);
		$this->item = $item;
	}


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
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
			$questionOption->isCustomInput = $this->isCustomInput;
			$questionOption->questionId = $this->item->id;
			$success &= $questionOption->save();

			if (is_array($this->commitmentOptions)) {
				foreach ($this->commitmentOptions as $commitmentOptionId) {
					Yii::$app->db->createCommand()->insert('commitments_by_questions', [
						'question_option_id'   => $questionOption->id,
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
	public function attributeLabels(): array {
		return [
			'name'          => 'Név',
			'description'   => 'Leírás',
			'order'         => 'Sorrend',
			'isCustomInput' => 'Egyedi szöveges válasz',
		];
	}


}
