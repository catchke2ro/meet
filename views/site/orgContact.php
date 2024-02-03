<?php

/**
 * @var $model Registration
 * @var $organization Organization
 */

use app\models\forms\Registration;
use app\models\Organization;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Üzenet küldése szervezetnek';
?>

<div class="site-signup pt-5">

	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-primary">
				<div class="card-header">
					<h1 class="card-title"><?=Html::encode($this->title)?></h1><br />
					<p class="card-subtitle" style="clear: both">Szervezet: <?=$organization->name;?></p>
				</div>
				<?php $form = ActiveForm::begin([
					'id'                 => 'form-org-contact',
					'fieldClass'         => \app\widgets\ActiveField::class,
					'enableClientScript' => false,
					'options'            => [
						'data-sitekey' => Yii::$app->params['recaptcha_site_key']
					]
				]); ?>
				<div class="card-body">
					<?=$form->field($model, 'name')->label('Név')->textInput()?>
					<?=$form->field($model, 'email')->label('E-mail cím')->textInput()?>
					<?=$form->field($model, 'message')->label('Üzenet')->textarea()?>
					<?=$form->field($model, 'orgId')->label(false)->hiddenInput();?>
					<?=$form->field($model, 'recaptchaResponse')->label(false)->textInput(['type' => 'hidden']);?>
				</div>
				<div class="card-footer">
					<?=Html::submitButton('Üzenet küldés', [
						'class' => 'btn btn-primary',
						'name'  => 'signup-button'
					])?>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>