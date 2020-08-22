<?php

/**
 * @var $model Registration
 */

use app\models\forms\Registration;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Regisztráció';
?>

<div class="site-signup pt-5">

	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card card-primary">
				<div class="card-header">
					<h1 class="card-title"><?=Html::encode($this->title)?></h1>
				</div>
				<?php $form = ActiveForm::begin(['id' => 'form-signup', 'enableClientScript' => false]); ?>
				<div class="card-body">
					<div class="row">
						<?=$form->field($model, 'namePrefix', ['options' => ['class' => 'form-group col-sm-3']])->label('Név előtag')->textInput()?>
						<?=$form->field($model, 'name', ['options' => ['class' => 'form-group col-sm-9']])->label('Név')->textInput(['autofocus' => true])?>
					</div>
					<?=$form->field($model, 'email')->label('E-mail cím')->textInput()?>
					<?=$form->field($model, 'orgRemoteId', ['options' => ['class' => 'form-group orgSelector']])
						->label('Kiválasztom az adataimat a központi adatbázisból')
						->dropDownList(array_merge(['' => ' - Nem szereplek az adatbázisban - ']));?>
					<fieldset class="orgData">
						<legend>Szervezet adatai</legend>
						<span>Csak amennyiben nem szerepel az adatbázisban</span>
						<?=$form->field($model, 'orgName')->label('Név');?>
						<?=$form->field($model, 'orgAddressZip')->label('Irányítószám');?>
						<?=$form->field($model, 'orgAddressCity')->label('Település');?>
						<?=$form->field($model, 'orgAddressStreet')->label('Utca, házszám...');?>
						<?=$form->field($model, 'orgPhone')->label('Telefonszám');?>
					</fieldset>
				</div>
				<div class="card-footer">
					<?=Html::submitButton('Regisztráció', ['class' => 'btn btn-primary', 'name' => 'signup-button'])?>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>