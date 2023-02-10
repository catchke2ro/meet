<?php

namespace app\modules\meet\models\forms;

use app\modules\meet\models\Module;
use Exception;
use Yii;

/**
 * Class ModuleEdit
 *
 * ModuleEdit form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class ModuleEdit extends ModuleCreate {

	/**
	 * @var Module|mixed
	 */
	private $module;


	/**
	 * @param Module $module
	 */
	public function loadModule(Module $module) {
		$this->module = $module;
		$this->name = $module->name;
		$this->slug = $module->slug;
		$this->threshold = $module->threshold;
		$this->description = $module->description;
		$this->descriptionPdf = $module->description_pdf;
	}


	/**
	 * Signs module up.
	 *
	 * @return Module|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function edit(): ?Module {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$this->module->name = $this->name;
			$this->module->slug = $this->slug;
			$this->module->description = $this->description;
			$this->module->description_pdf = $this->descriptionPdf;
			$this->module->threshold = $this->threshold;
			$success &= $this->module->save();

			$transaction->commit();

			return $success ? $this->module : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}