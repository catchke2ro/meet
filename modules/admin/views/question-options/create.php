<?php
/**
 * @var $model QuestionOptionCreate
 * @var $item QuestionItem
 */

use app\modules\admin\models\forms\QuestionOptionCreate;
use app\models\QuestionItem;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Kérdés létrehozása';
?>


<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h3>Kategória: <?=$item->category->name;?></h3>
				<h3>Kérdés: <?=$item->name;?></h3>
			</div>
			<?php $form = ActiveForm::begin(['id' => 'form-question-option-create', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'name')->label('Név')->textInput()?>
				<?=$form->field($model, 'description')->label('Leírás')->textarea()?>
				<?=$form->field($model, 'order')->label('Sorrend')->textInput(['type' => 'number'])?>
				<?=$form->field($model, 'isCustomInput')->label('Egyedi szöveges válasz', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'commitmentOptions')->label('Kapcsolódó vállalás opciók')
					->dropDownList($commitmentOptions, ['options' => $commitmentOptionsOptions, 'multiple' => true, 'encode' => false, 'style' => 'height: 300px'])
					->hint('Több kijelölése Ctrl-al (Mac: Cmd)');?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
