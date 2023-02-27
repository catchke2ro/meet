<?php

/**
 * @var $model ResetPassword
 */

use app\models\forms\ResetPassword;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Jelszó visszaállítása';
?>

<div class="site-signup pt-5">

	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-primary">
				<div class="card-header">
					<h1 class="card-title"><?=Html::encode($this->title)?></h1>
				</div>
				<?php $form = ActiveForm::begin([
					'id'                 => 'form-reset-password',
					'fieldClass'         => \app\widgets\ActiveField::class,
					'enableClientScript' => false,
					'options'            => [
						'data-sitekey' => Yii::$app->params['recaptcha_site_key']
					]
				]); ?>
				<div class="card-body">
					<?=$form->field($model, 'password')->label('Jelszó')->passwordInput()?>
					<?=$form->field($model, 'passwordConfirm')->label('Jelszó megerősítése')->passwordInput()?>
					<?=$form->field($model, 'recaptcha_response')->label(false)->textInput(['type' => 'hidden']);?>
					<?=$form->field($model, 'token')->label(false)->textInput(['type' => 'hidden', 'value' => $token]);?>
				</div>
				<div class="card-footer">
					<?=Html::submitButton('Jelszó visszaállítása', [
						'class' => 'btn btn-primary',
						'name'  => 'reset-password-button'
					])?>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>