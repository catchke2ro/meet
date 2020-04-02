<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Belépés';
?>

<div class="site-signup">
	<h1><?=Html::encode($this->title)?></h1>
	<div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'form-login', 'enableClientScript' => false]); ?>
			<?=$form->field($model, 'email')->label('E-mail cím')->textInput(['autofocus' => true])?>
			<?=$form->field($model, 'password')->label('Jelszó')->passwordInput()?>
			<div class="form-group">
				<?=Html::submitButton('Belépés', ['class' => 'btn btn-primary', 'name' => 'signup-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>