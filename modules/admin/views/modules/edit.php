<?php

/**
 * @var $model ModuleCreate
 */

use app\modules\admin\models\forms\ModuleCreate;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Modul módosítása';
?>


<div class="row">
	<div class="col-12">
		<div class="card">
			<?php $form = ActiveForm::begin(['id' => 'form-module-create', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'name')->label('Név')->textInput()?>
				<?=$form->field($model, 'slug')->label('Slug')->textInput()?>
				<?=$form->field($model, 'threshold')->label('Határpontszám')->textInput(['type' => 'number'])?>
				<?=$form->field($model, 'description')->label('Leírás')->textarea()?>
				<?=$form->field($model, 'descriptionPdf')->label('Leírás PDF-ben')->textarea()?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
