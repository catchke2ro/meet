<?php


namespace app\widgets;

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/**
 * Class ActiveField
 *
 * @package app\widgets
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class ActiveField extends \yii\bootstrap4\ActiveField {


	/**
	 * @param array $options
	 * @param false $enclosedByLabel
	 *
	 * @return \yii\bootstrap4\ActiveField|\yii\widgets\ActiveField
	 */
	public function checkbox($options = [], $enclosedByLabel = false) {
		Html::addCssClass($options, 'custom-control-input');
		if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
			$this->addErrorClassIfNeeded($options);
		}

		return parent::checkbox($options, $enclosedByLabel);
	}


}
