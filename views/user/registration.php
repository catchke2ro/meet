<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Regisztráció';
?>

<div class="site-signup">
	<h1><?=Html::encode($this->title)?></h1>
	<div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'form-signup', 'enableClientScript' => false]); ?>
			<?=$form->field($model, 'email')->label('E-mail cím')->textInput(['autofocus' => true])?>
			<?=$form->field($model, 'password')->label('Jelszó')->passwordInput()?>
			<?=$form->field($model, 'passwordConfirm')->label('Jelszó megeerősítése')->passwordInput()?>
			<?=$form->field($model, 'orgRemoteId')->label('Kiválasztom az adataimat a központi adatbázisból')
				->dropDownList(['' => ' - Nem szereplek az adatbázisban - ', 13 => 'Egy szervezet']);?>
			<fieldset>
				<legend>Szervezet adatai</legend>
				<span>Csak amennyiben nem szerepel az adatbázisban</span>
				<?=$form->field($model, 'orgName')->label('Név'); ?>
				<?=$form->field($model, 'orgAddress')->label('Cím'); ?>
				<?=$form->field($model, 'orgPhone')->label('Telefonszám'); ?>
				<?=$form->field($model, 'orgCompanyNumber')->label('Cégjegyzékszám'); ?>
				<?=$form->field($model, 'orgTaxNumber')->label('Adószám'); ?>
			</fieldset>
			<div class="form-group">
				<?=Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>