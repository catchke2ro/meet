<?php

namespace app\models\forms\admin;

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
 * @package app\models\forms\admin
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentCategoryCreate extends Model {

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var int
	 */
	public $order;

	/**
	 * @var bool
	 */
	public $hasInstances;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var array
	 */
	public $orgTypes;

	/**
	 * @var int
	 */
	public $questionCategoryInstId;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['name', 'trim'],
			['name', 'required'],
			['order', 'number'],
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
			$commitmentCategory->has_instances = $this->hasInstances;
			$commitmentCategory->order = $this->order;
			$commitmentCategory->question_category_inst_id = $this->questionCategoryInstId;
			$success &= $commitmentCategory->save();

			if (is_array($this->orgTypes)) {
				foreach ($this->orgTypes as $orgType) {
					$commitmentCategoryOrgType = new CommitmentCategoryOrgType();
					$commitmentCategoryOrgType->org_type_id = $orgType;
					$commitmentCategoryOrgType->commitment_category_id = $commitmentCategory->id;
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
	public function attributeLabels() {
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