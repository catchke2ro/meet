<?php

/**
 * @var UserEdit $model
 */

use app\modules\meet\models\forms\UserEdit;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$user = $model->user;

$this->title = 'Felhasználó szerkesztése';
?>


<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h3>Felhasználó adatai</h3>
			</div>
			<div class="card-body">
				<ul>
					<li><strong>Felhasználónév</strong>: <?=$user->username;?></li>
					<li><strong>E-mail cím</strong>: <?=$user->email;?></li>
					<li><strong>Név</strong>: <?=$user->person ? $user->person->nev : '';?></li>
					<li><strong>Aktív</strong>: <?=$user->is_active ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>';?></li>
					<li><strong>Adminisztrátor</strong>: <?=$user->is_admin ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>';?></li>
					<li><strong>Adminisztrátor által jóváhagyva</strong>: <?=$user->is_approved_admin ? '<i class="fa fa-close"></i>' : '<i class="fa fa-close"></i>';?></li>
					<li><strong>Vezető által jóváhagyva</strong>: <?=$user->is_approved_boss ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>';?></li>
					<?php if ($user->organization) { ?>
						<li><strong>Szervezet</strong>: <?=$user->organization->nev;?></li>
					<?php } ?>

				</ul>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<?php $form = ActiveForm::begin(['id' => 'form-user-create', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'username')->label('Név')->textInput()?>
				<?=$form->field($model, 'email')->label('E-mail cím')->textInput()?>
				<?=$form->field($model, 'isActive')->label('Aktív', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'isAdmin')->label('Adminisztrátor felhasználó', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'isApprovedAdmin')->label('Adminisztártor által elfogadva', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'isApprovedBoss')->label('Vezető által elfogadva', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'orgRemoteId', ['options' => ['class' => 'form-group orgSelector']])->label('Szervezet adatai adatbázisból')
					->dropDownList(['' => ' - Nem szerepel az adatbázisban - '] + $orgList, ['disabled' => 'disabled']);?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
