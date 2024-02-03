<?php

/**
 * @var $model        OrganizationEdit
 * @var $organization Organization
 */

use app\models\Organization;
use app\models\OrganizationType;
use app\modules\admin\models\forms\OrganizationEdit;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Szervezet módosítása';
?>


<div class="row">
	<div class="col-12">
		<?php $form = ActiveForm::begin(['id' => 'form-organization-edit', 'enableClientScript' => false]); ?>
		<div class="card">
			<div class="card-header">
				<h5 class="card-title">Szervezet módosítása</h5>
			</div>
			<div class="card-body">
				<fieldset>
					<legend>Szervezet adatai</legend>
					<?=$form->field($model, 'orgTypeId', ['options' => ['class' => 'form-group']])->dropDownList(OrganizationType::getList());?>
					<?=$form->field($model, 'orgName')->label('Név');?>
					<?=$form->field($model, 'orgAddressZip')->label('Irányítószám');?>
					<?=$form->field($model, 'orgAddressCity')->label('Település');?>
					<?=$form->field($model, 'orgAddressStreet')->label('Utca, házszám...');?>
					<?=$form->field($model, 'orgPhone')->label('Telefonszám')->textInput(['type' => 'tel']);?>
					<?=$form->field($model, 'orgEmail')->label('E-mail cím')->textInput(['type' => 'email']);?>
				</fieldset>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h6>MEET referens adatai</h6>
			</div>
			<div class="card-body">
				<fieldset>
					<?=$form->field($model, 'refereeName')->label('Név');?>
					<?=$form->field($model, 'refereeEmail')->label('E-mail cím')->textInput(['type' => 'email']);?>
				</fieldset>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h6>Lelkész adatai</h6>
			</div>
			<div class="card-body">

				<fieldset>
					<?=$form->field($model, 'pastorName')->label('Név');?>
					<?=$form->field($model, 'pastorEmail')->label('E-mail cím')->textInput(['type' => 'email']);?>
				</fieldset>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h6>Felügyelő adatai</h6>
			</div>
			<div class="card-body">
				<fieldset>
					<?=$form->field($model, 'superintendentName')->label('MEET referens neve');?>
				</fieldset>
			</div>
		</div>
		<div class="card">
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<ul>
					<li>
						<strong>Hozzárendelt felhasználó</strong>:
						<?php if (($user = $model->organization->meetReferee?->user)) { ?>
							<a href="/admin/users/edit/<?=$user?->id;?>">#<?=$user?->id;?> - <?=$user->email;?></a>
						<?php } ?>
					</li>
					<li>
						<strong>Hozzájáruló nyilatkozat</strong>:
						<?php if ($model->organization->authorizationFilename) { ?>
							<a href="/admin/storage-download?file=authorizations/<?=$model->organization->authorizationFilename;?>" target="_blank">
								<?=$model->organization->authorizationFilename;?>
							</a>
						<?php } ?>
					</li>
				</ul>
			</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
