<?php

namespace app\components\validators;

use Yii;
use yii\base\Model;
use yii\validators\Validator;

/**
 * Class recaptcha
 *
 * @package app\components\validators
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class recaptcha extends Validator {

	/**
	 * @var \ReCaptcha\ReCaptcha
	 */
	protected $recaptcha;


	/**
	 * recaptcha constructor.
	 *
	 * @param array $config
	 */
	public function __construct($config = []) {
		parent::__construct($config);
		$this->recaptcha = new \ReCaptcha\ReCaptcha(Yii::$app->params['recaptcha_secret_key']);
	}


	/**
	 * @param Model  $model
	 * @param string $attribute
	 */
	public function validateAttribute($model, $attribute) {
		// extract the attribute value from it's model object
		$value = $model->$attribute;
		if (!$this->recaptcha->setExpectedHostname(Yii::$app->request->getHostName())
					   ->verify($value, Yii::$app->request->getUserIP())
					   ->isSuccess()) {
			$this->addError($model, $attribute, 'CAPTCHA hiba!');
		}
	}


}
