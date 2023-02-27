<?php

/**
 * @var $model ForgotPassword
 */

use app\models\forms\ForgotPassword;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Elfelejtett jelszó';
?>

<div class="site-signup pt-5">

	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-primary">
				<div class="card-header">
					<h1 class="card-title"><?=Html::encode($this->title)?></h1>
				</div>
				<?php $form = ActiveForm::begin([
					'id'                 => 'form-forgot-password',
					'fieldClass'         => \app\widgets\ActiveField::class,
					'enableClientScript' => false,
					'options'            => [
						'data-sitekey' => Yii::$app->params['recaptcha_site_key']
					]
				]); ?>
				<div class="card-body">
					<?=$form->field($model, 'email')->label('E-mail cím')->textInput()?>
					<?=$form->field($model, 'recaptcha_response')->label(false)->textInput(['type' => 'hidden']);?>
				</div>
				<div class="card-footer">
					<?=Html::submitButton('Küldés', [
						'class' => 'btn btn-primary',
						'name'  => 'forgot-password-button'
					])?>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>