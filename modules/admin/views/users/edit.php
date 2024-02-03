<?php

/**
 * @var UserEdit $model
 */

use app\modules\admin\models\forms\UserEdit;
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
					<li><strong>Név</strong>: <?=$user->person ? $user->person->name : '';?></li>
					<li><strong>Aktív</strong>: <?=$user->isActive ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>';?></li>
					<li><strong>Adminisztrátor</strong>: <?=$user->isAdmin() ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>';?></li>
					<li><strong>Adminisztrátor által jóváhagyva</strong>: <?=$user->isApprovedAdmin ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>';?></li>
					<li><strong>Vezető által jóváhagyva</strong>: <?=$user->isApprovedBoss ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>';?></li>
					<?php if ($user->organization) { ?>
						<li><strong>Szervezet</strong>: <?=$user->organization->name;?></li>
					<?php } ?>
				</ul>
			</div>
			<?php if ($user->person) { ?>
				<div class="card-header">
					<h3>Hozzárendelt személy (<?=$user->person->typeLabel;?>)</h3>
				</div>
				<div class="card-body">
					<ul>
						<li><strong>Felhasználónév</strong>: <?=$user->username;?></li>
						<li><strong>E-mail cím</strong>: <?=$user->email;?></li>
						<li><strong>Név</strong>: <?=$user->person ? $user->person->name : '';?></li>
						<li><strong>Aktív</strong>: <?=$user->isActive ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>';?></li>
					</ul>
				</div>
			<?php } ?>

		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<?php $form = ActiveForm::begin(['id' => 'form-user-create', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'username')->label('Felhasználónév')->textInput()?>
				<?=$form->field($model, 'email')->label('E-mail cím')->textInput()?>
				<?=$form->field($model, 'isActive')->label('Aktív', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'isAdmin')->label('Adminisztrátor felhasználó', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'isApprovedAdmin')->label('Adminisztártor által elfogadva', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'isApprovedBoss')->label('Vezető által elfogadva', ['class' => 'custom-control-label'])->checkbox()?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
