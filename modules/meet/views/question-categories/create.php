<?php
/**
 * @var $model QuestionCategoryCreate
 * @var $orgTypes array
 */

use app\modules\meet\models\forms\QuestionCategoryCreate;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Kérdés kategória létrehozása';
?>


<div class="row">
	<div class="col-12">
		<div class="card">
			<?php $form = ActiveForm::begin(['id' => 'form-question-category-create', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'name')->label('Név')->textInput()?>
				<?=$form->field($model, 'description')->label('Leírás')->textarea()?>
				<?=$form->field($model, 'hasInstances')->label('Példányosítható', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'order')->label('Sorrend')->textInput(['type' => 'number'])?>
				<?=$form->field($model, 'orgTypes')->label('Típusok')->checkboxList($orgTypes)?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
