<?php

namespace app\models\forms\admin;

use app\models\Module;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class ModuleCreate
 *
 * ModuleCreate form
 *
 * @package app\models\forms\admin
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class ModuleCreate extends Model {

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $slug;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var string
	 */
	public $descriptionPdf;

	/**
	 * @var int
	 */
	public $threshold;


	/**
	 * @inheritdoc
	 */
	public function rules() {
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
			$module->description_pdf = $this->descriptionPdf;
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
	public function attributeLabels() {
		return [
			'name'           => 'Név',
			'slug'           => 'Slug',
			'description'    => 'Leírás',
			'descriptionPdf' => 'Leírás PDF-ben',
			'threshold'      => 'Határpontszám'
		];
	}


}