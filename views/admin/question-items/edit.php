<?php

/**
 * @var $model QuestionItemCreate
 * @var $category QuestionCategory
 */

use app\models\forms\admin\QuestionItemCreate;
use app\models\QuestionCategory;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Kérdés módosítása';
?>


<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<span>Kategória: <?=$category->name;?></span>
			</div>
			<?php $form = ActiveForm::begin(['id' => 'form-question-item-create', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'name')->label('Név')->textInput()?>
				<?=$form->field($model, 'description')->label('Leírás')->textarea()?>
				<?=$form->field($model, 'order')->label('Sorrend')->textInput(['type' => 'number'])?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
