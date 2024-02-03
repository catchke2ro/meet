<?php

namespace app\modules\admin\models\forms;

use app\models\Module;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class ModuleCreate
 *
 * ModuleCreate form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class ModuleCreate extends Model {

	public ?string $name = null;

	public ?string $slug = null;

	public ?string $description = null;

	public ?string $descriptionPdf = null;

	public ?int $threshold = null;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
		return [
			['name', 'trim'],
			['name', 'required'],
			['slug', 'trim'],
			['slug', 'required'],
			['threshold', 'required'],
			['threshold', 'number'],
			['description', 'safe'],
			['descriptionPdf', 'safe'],
		];
	}


	/**
	 * @return Module|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function create(): ?Module {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$module = new Module();
			$module->name = $this->name;
			$module->slug = $this->slug;
			$module->description = $this->description;
			$module->descriptionPdf = $this->descriptionPdf;
			$module->threshold = $this->threshold;
			$success &= $module->save();

			$transaction->commit();

			return $success ? $module : null;
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
			'name'           => 'Név',
			'slug'           => 'Slug',
			'description'    => 'Leírás',
			'descriptionPdf' => 'Leírás PDF-ben',
			'threshold'      => 'Határpontszám'
		];
	}


}
