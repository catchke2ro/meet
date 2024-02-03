<?php

/**
 * @var $model CommitmentCategoryCreate
 * @var $orgTypes array
 */

use app\modules\admin\models\forms\CommitmentCategoryCreate;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Vállalás kategória módosítása';
?>


<div class="row">
	<div class="col-12">
		<div class="card">
			<?php $form = ActiveForm::begin(['id' => 'form-commitment-category-create', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'name')->label('Név')->textInput()?>
				<?=$form->field($model, 'description')->label('Leírás')->textarea()?>
				<?=$form->field($model, 'hasInstances')->label('Példányosítható', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'order')->label('Sorrend')->textInput(['type' => 'number'])?>
				<?=$form->field($model, 'orgTypes')->label('Típusok')->checkboxList($orgTypes)?>
				<?=$form->field($model, 'questionCategoryInstId')->label('Kérdés kategória pár (példányosításhoz)')
					->dropDownList($questionCategories, ['prompt' => '-'])?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
