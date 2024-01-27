<?php

namespace app\modules\meet\models\forms;

use app\modules\meet\models\CommitmentCategory;
use app\modules\meet\models\CommitmentItem;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class CommitmentItemCreate
 *
 * CommitmentItemCreate form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentItemCreate extends Model {

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
	 * @var int
	 */
	public $monthStep;

	/**
	 * @var int
	 */
	public $monthsMin;

	/**
	 * @var int
	 */
	public $monthsMax;

	/**
	 * @var CommitmentCategory
	 */
	private $category;

	/**
	 * @var bool
	 */
	public $isActive;


	/**
	 * CommitmentItemCreate constructor.
	 *
	 * @param CommitmentCategory $category
	 * @param array              $config
	 */
	public function __construct(CommitmentCategory $category, $config = []) {
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
			['order', 'required'],
			['description', 'safe'],
			['isActive', 'safe'],
			['monthStep', 'integer'],
			['monthsMin', 'integer'],
			['monthsMax', 'integer'],
		];
	}


	/**
	 * @return CommitmentItem|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function create(): ?CommitmentItem {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$commitmentItem = new CommitmentItem();
			$commitmentItem->name = $this->name;
			$commitmentItem->description = $this->description;
			$commitmentItem->order = $this->order;
			$commitmentItem->is_active = $this->isActive;
			$commitmentItem->commitment_category_id = $this->category->id;
			$commitmentItem->month_step = $this->monthStep;
			$commitmentItem->months_max = $this->monthsMax;
			$commitmentItem->months_min = $this->monthsMin;
			$success &= $commitmentItem->save();

			$transaction->commit();

			return $success ? $commitmentItem : null;
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
			'isActive'    => 'Aktív',
			'monthStep'   => 'Hónapok lépésköz',
			'monthsMin'   => 'Minimum hónapok',
			'monthsMax'   => 'Maximum hónapok',
		];
	}


}