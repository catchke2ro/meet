<?php

namespace app\modules\admin\models\forms;

use app\models\CommitmentCategory;
use app\models\CommitmentItem;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class CommitmentItemCreate
 *
 * CommitmentItemCreate form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentItemCreate extends Model {

	public ?string $name = null;

	public ?int $order = null;

	public ?string $description = null;

	public ?int $monthStep = null;

	public ?int $monthsMin = null;

	public ?int $monthsMax = null;

	public ?CommitmentCategory $category = null;

	public ?bool $isActive = null;


	/**
	 * CommitmentItemCreate constructor.
	 *
	 * @param CommitmentCategory $category
	 * @param array              $config
	 */
	public function __construct(CommitmentCategory $category, array $config = []) {
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
			$commitmentItem->isActive = $this->isActive;
			$commitmentItem->commitmentCategoryId = $this->category->id;
			$commitmentItem->monthStep = $this->monthStep;
			$commitmentItem->monthsMax = $this->monthsMax;
			$commitmentItem->monthsMin = $this->monthsMin;
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
	public function attributeLabels(): array {
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
