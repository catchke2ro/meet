<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Felhasználó szerkesztése';
?>


<div class="row">
	<div class="col-12">
		<div class="card">
			<?php $form = ActiveForm::begin(['id' => 'form-user-create', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'name')->label('Név')->textInput()?>
				<?=$form->field($model, 'email')->label('E-mail cím')->textInput()?>
				<?=$form->field($model, 'password')->label('Új jelszó')->passwordInput()?>
				<?=$form->field($model, 'passwordConfirm')->label('Új jelszó megeerősítése')->passwordInput()?>
				<?=$form->field($model, 'isAdmin')->label('Adminisztrátor felhaszáló', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'isApprovedAdmin')->label('Adminisztártor által elfogadva', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'isApprovedBoss')->label('Vezető által elfogadva', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'orgRemoteId')->label('Szervezet adatai adatbázisból')
					->dropDownList(['' => ' - Nem szerepel az adatbázisban - ', 13 => 'Egy szervezet']);?>
				<fieldset>
					<legend>Szervezet adatai</legend>
					<span>Csak amennyiben nem szerepel az adatbázisban</span>
					<?=$form->field($model, 'orgName')->label('Név'); ?>
					<?=$form->field($model, 'orgAddress')->label('Cím'); ?>
					<?=$form->field($model, 'orgPhone')->label('Telefonszám'); ?>
					<?=$form->field($model, 'orgCompanyNumber')->label('Cégjegyzékszám'); ?>
					<?=$form->field($model, 'orgTaxNumber')->label('Adószám'); ?>
				</fieldset>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
