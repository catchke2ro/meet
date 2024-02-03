<?php

/**
 * @var $model Registration
 */

use app\models\forms\Registration;
use app\models\OrganizationType;
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
				<?php $form = ActiveForm::begin([
					'id'                 => 'form-signup',
					'fieldClass'         => \app\widgets\ActiveField::class,
					'enableClientScript' => false,
					'options'            => [
						'enctype'      => 'multipart/form-data',
						'data-sitekey' => Yii::$app->params['recaptcha_site_key']
					]
				]); ?>
				<div class="card-body">
					<fieldset class="border p-3 mb-3">
						<legend>Regisztráló (megbízott személy) adatai</legend>
						<?=$form->field($model, 'refereeName')->label('Név')->textInput(['autofocus' => true])?>
						<?=$form->field($model, 'refereeEmail')->label('E-mail cím')->textInput(['type' => 'email'])?>
						<?=$form->field($model, 'password')->label('Jelszó')->passwordInput()?>
						<?=$form->field($model, 'passwordConfirm')->label('Jelszó megerősítése')->passwordInput()?>
					</fieldset>
					<fieldset class="orgData border p-3 mb-3">
						<legend>Szervezeti egység adatai</legend>
						<?=$form->field($model, 'orgType', ['options' => ['class' => 'form-group orgTypeSelector']])
							->dropDownList(OrganizationType::getList());?>
						<?=$form->field($model, 'orgName')->label('Név');?>
						<?=$form->field($model, 'orgAddressZip')->label('Irányítószám');?>
						<?=$form->field($model, 'orgAddressCity')->label('Település');?>
						<?=$form->field($model, 'orgAddressStreet')->label('Utca, házszám...');?>
						<?=$form->field($model, 'orgPhone')->label('Telefonszám')->textInput(['type' => 'tel']);?>
						<?=$form->field($model, 'orgEmail')->label('E-mail cím')->textInput(['type' => 'email']);?>
					</fieldset>
					<fieldset class="orgData border p-3 mb-3">
						<legend>Lelkész adatai</legend>
						<?=$form->field($model, 'pastorName')->label('Név');?>
						<?=$form->field($model, 'pastorEmail')->label('E-mail cím')->textInput(['type' => 'email']);?>
					</fieldset>
					<fieldset class="orgData border p-3 mb-3">
						<legend>Felügyelő adatai</legend>
						<?=$form->field($model, 'superintendentName')->label('Név');?>
					</fieldset>

					<fieldset class="border p-3 mb-3">
						<legend>Meghatalmazás</legend>
						<?=$form->field($model, 'pdf')->label(false)
							->hint('<p>Csak pdf fájl tölthető fel. <br /> Meghatalmazáshoz határozat minta itt érhető el: <a href="/dokumentumok" target="_blank">Dokumentumok</a> <br /> További információ az <a href="/afe" target="_blank">Általános Együttműködési Feltételekben</a></p>')
							->fileInput()?>
					</fieldset>

					<?=$form->field($model, 'terms')
						->label('Regisztrációmmal hozzájárulok személyes adataim kezeléséhez, amelyet a <a href="https://zsinat.lutheran.hu/torvenyek/toervenyek/4-2018.-viii.-28.-orszagos-szabalyrendelet-a-magyarorszagi-evangelikus-egyhaz-adatvedelmi-es-adatbiztonsagi-szabalyzatarol-melleklet/B5_MEE%20adatvedelmi%20szabalyzata_20180626.pdf/view" target="_blank">Magyarországi Evangélikus Egyház 4/2018. (VIII. 28.) országos szabályrendeletében</a> foglalt adatvédelmi és adatbiztonsági szabályzat határoz meg.', ['class' => 'custom-control-label'])
						->checkbox();?>
					<?=$form->field($model, 'terms2')
						->label('Az <a href="/afe" target="_blank">Általános Együttműködési Feltételeket</a> elolvastam.', ['class' => 'custom-control-label'])
						->checkbox();?>
					<?=$form->field($model, 'recaptchaResponse')->label(false)->textInput(['type' => 'hidden']);?>
				</div>
				<div class="card-footer">
					<?=Html::submitButton('Regisztráció', [
						'class' => 'btn btn-primary',
						'name'  => 'signup-button'
					])?>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
