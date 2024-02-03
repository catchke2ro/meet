<?php

namespace app\modules\admin\models\forms;

use app\models\QuestionCategory;
use app\models\QuestionCategoryOrgType;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class QuestionCategoryCreate
 *
 * QuestionCategoryCreate form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionCategoryCreate extends Model {

	public ?string $name = null;

	public ?int $order = null;

	public ?bool $hasInstances = null;

	public ?string $description = null;

	public ?array $orgTypes = null;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
		return [
			['name', 'trim'],
			['name', 'required'],
			['order', 'number'],
			['hasInstances', 'boolean'],
			['orgTypes', 'safe'],
			['description', 'safe'],
		];
	}


	/**
	 * @return QuestionCategory|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function create(): ?QuestionCategory {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$questionCategory = new QuestionCategory();
			$questionCategory->name = $this->name;
			$questionCategory->description = $this->description;
			$questionCategory->hasInstances = $this->hasInstances;
			$questionCategory->order = $this->order;
			$success &= $questionCategory->save();

			if (is_array($this->orgTypes)) {
				foreach ($this->orgTypes as $orgType) {
					$questionCategoryOrgType = new QuestionCategoryOrgType();
					$questionCategoryOrgType->orgTypeId = $orgType;
					$questionCategoryOrgType->questionCategoryId = $questionCategory->id;
					$success &= $questionCategoryOrgType->save();
				}
			}

			$transaction->commit();

			return $success ? $questionCategory : null;
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
			'name'         => 'Név',
			'description'  => 'Leírás',
			'hasInstances' => 'Példányosítható',
			'order'        => 'Sorrend',
			'orgTypes'     => 'Típusok',
		];
	}


}
