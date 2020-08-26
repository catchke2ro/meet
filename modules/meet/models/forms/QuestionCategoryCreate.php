<?php

namespace app\modules\meet\models\forms;

use app\modules\meet\models\QuestionCategory;
use app\modules\meet\models\QuestionCategoryOrgType;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class QuestionCategoryCreate
 *
 * QuestionCategoryCreate form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class QuestionCategoryCreate extends Model {

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
			$questionCategory->has_instances = $this->hasInstances;
			$questionCategory->order = $this->order;
			$success &= $questionCategory->save();

			if (is_array($this->orgTypes)) {
				foreach ($this->orgTypes as $orgType) {
					$questionCategoryOrgType = new QuestionCategoryOrgType();
					$questionCategoryOrgType->org_type_id = $orgType;
					$questionCategoryOrgType->question_category_id = $questionCategory->id;
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
	public function attributeLabels() {
		return [
			'name'         => 'Név',
			'description'  => 'Leírás',
			'hasInstances' => 'Példányosítható',
			'order'        => 'Sorrend',
			'orgTypes'     => 'Típusok',
		];
	}


}