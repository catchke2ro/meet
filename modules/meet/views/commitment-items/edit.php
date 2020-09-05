<?php

/**
 * @var $model CommitmentItemCreate
 * @var $category CommitmentCategory
 */

use app\modules\meet\models\forms\CommitmentItemCreate;
use app\modules\meet\models\CommitmentCategory;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Vállalás módosítása';
?>


<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<span>Kategória: <?=$category->name;?></span>
			</div>
			<?php $form = ActiveForm::begin(['id' => 'form-commitment-item-create', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'name')->label('Név')->textInput()?>
				<?=$form->field($model, 'description')->label('Leírás')->textarea()?>
				<?=$form->field($model, 'order')->label('Sorrend')->textInput(['type' => 'number'])?>
				<?=$form->field($model, 'isActive')->label('Aktív', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'monthStep')->label('Hónapok lépésköz')->textInput(['type' => 'number'])?>
				<?=$form->field($model, 'monthsMin')->label('Minimum hónapok')->textInput(['type' => 'number'])?>
				<?=$form->field($model, 'monthsMax')->label('Maximum hónapok')->textInput(['type' => 'number'])?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
