<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Belépés';
?>

<div class="site-signin pt-5">

	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-primary">
				<div class="card-header">
					<h1 class="card-title"><?=Html::encode($this->title)?></h1>
				</div>
				<?php $form = ActiveForm::begin(['id' => 'form-login', 'enableClientScript' => false]); ?>
				<div class="card-body">
					<div class="text-center mb-2">
						<p class="mb-1">Amennyiben még nem regisztrált, itt megteheti:</p>
						<a class="btn btn-secondary" href="/regisztracio">Regisztráció</a>
					</div>
					<?=$form->field($model, 'email')->label('E-mail cím')->textInput(['autofocus' => true])?>
					<?=$form->field($model, 'password')->label('Jelszó')->passwordInput()?>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-md-6">
							<?=Html::submitButton('Belépés', ['class' => 'btn btn-primary', 'name' => 'signup-button'])?>
						</div>
						<div class="col-md-6 text-right forgotPwdLinkContainer">
							<?=Html::a('Elfelejtettem a jelszavam', ['user/forgot-password'])?>
						</div>
					</div>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>


</div>