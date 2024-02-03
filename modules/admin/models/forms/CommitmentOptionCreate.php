<?php

namespace app\modules\admin\models\forms;

use app\models\CommitmentItem;
use app\models\CommitmentOption;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class CommitmentOptionCreate
 *
 * CommitmentOptionCreate form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentOptionCreate extends Model {

	public ?string $name = null;

	public ?int $order = null;

	public ?string $description = null;

	public ?bool $isCustomInput = null;

	public ?int $score = null;

	public ?CommitmentItem $item = null;


	/**
	 * CommitmentOptionCreate constructor.
	 *
	 * @param CommitmentItem $item
	 * @param array          $config
	 */
	public function __construct(CommitmentItem $item, array $config = []) {
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
			['order', 'required'],
			['description', 'safe'],
			['isCustomInput', 'safe'],
			['score', 'number'],
			['score', 'required'],
		];
	}


	/**
	 * @param array       $data
	 * @param string|null $formName
	 *
	 * @return bool
	 */
	public function load($data, $formName = null): bool {
		if (empty($data['CommitmentOptionCreate']['order'])) {
			$data['CommitmentOptionCreate']['order'] = CommitmentOption::find()->where(['commitment_id' => $this->item->id])->max('`order`') + 1;
		}

		return parent::load($data, $formName);
	}


	/**
	 * @return CommitmentOption|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function create(): ?CommitmentOption {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$commitmentOption = new CommitmentOption();
			$commitmentOption->name = $this->name;
			$commitmentOption->description = $this->description;
			$commitmentOption->order = $this->order;
			$commitmentOption->isCustomInput = $this->isCustomInput;
			$commitmentOption->score = $this->score;
			$commitmentOption->commitmentId = $this->item->id;
			$success &= $commitmentOption->save();

			$transaction->commit();

			return $success ? $commitmentOption : null;
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
			'score'         => 'Pontszám',
		];
	}


}
