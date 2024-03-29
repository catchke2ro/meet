<?php

namespace app\modules\admin\models\forms;

use app\models\CommitmentCategory;
use app\models\CommitmentCategoryOrgType;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class CommitmentCategoryCreate
 *
 * CommitmentCategoryCreate form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentCategoryCreate extends Model {

	public ?string $name = null;

	public ?int $order = null;

	public ?bool $hasInstances = null;

	public ?string $description = null;

	public ?array $orgTypes = null;

	public CommitmentCategory|string|null $questionCategoryInstId = null;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
		return [
			['name', 'trim'],
			['name', 'required'],
			['order', 'number'],
			['order', 'required'],
			['hasInstances', 'boolean'],
			['orgTypes', 'safe'],
			['description', 'safe'],
			['questionCategoryInstId', 'safe'],
		];
	}


	/**
	 * @return CommitmentCategory|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function create(): ?CommitmentCategory {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$commitmentCategory = new CommitmentCategory();
			$commitmentCategory->name = $this->name;
			$commitmentCategory->description = $this->description;
			$commitmentCategory->hasInstances = $this->hasInstances;
			$commitmentCategory->order = $this->order;
			$commitmentCategory->questionCategoryInstId = $this->questionCategoryInstId;
			$success &= $commitmentCategory->save();

			if (is_array($this->orgTypes)) {
				foreach ($this->orgTypes as $orgType) {
					$commitmentCategoryOrgType = new CommitmentCategoryOrgType();
					$commitmentCategoryOrgType->orgTypeId = $orgType;
					$commitmentCategoryOrgType->commitmentCategoryId = $commitmentCategory->id;
					$success &= $commitmentCategoryOrgType->save();
				}
			}

			$transaction->commit();

			return $success ? $commitmentCategory : null;
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
			'name'                   => 'Név',
			'description'            => 'Leírás',
			'hasInstances'           => 'Példányosítható',
			'order'                  => 'Sorrend',
			'orgTypes'               => 'Típusok',
			'questionCategoryInstId' => 'Kérdés kategória pár',
		];
	}


}
