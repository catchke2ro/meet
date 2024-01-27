<?php

namespace app\modules\meet\models\forms;

use app\modules\meet\models\CommitmentCategory;
use app\modules\meet\models\CommitmentItem;
use app\modules\meet\models\CommitmentOption;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class CommitmentOptionCreate
 *
 * CommitmentOptionCreate form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentOptionCreate extends Model {

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
	 * @var int
	 */
	public $score;

	/**
	 * @var CommitmentItem
	 */
	private $item;


	/**
	 * CommitmentOptionCreate constructor.
	 *
	 * @param CommitmentItem $item
	 * @param array          $config
	 */
	public function __construct(CommitmentItem $item, $config = []) {
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
			['order', 'required'],
			['description', 'safe'],
			['isCustomInput', 'safe'],
			['score', 'number'],
			['score', 'required'],
		];
	}


	/**
	 * @param $data
	 * @param $formName
	 *
	 * @return bool
	 */
	public function load($data, $formName = null) {
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
			$commitmentOption->is_custom_input = $this->isCustomInput;
			$commitmentOption->score = $this->score;
			$commitmentOption->commitment_id = $this->item->id;
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
	public function attributeLabels() {
		return [
			'name'          => 'Név',
			'description'   => 'Leírás',
			'order'         => 'Sorrend',
			'isCustomInput' => 'Egyedi szöveges válasz',
			'score'         => 'Pontszám',
		];
	}


}