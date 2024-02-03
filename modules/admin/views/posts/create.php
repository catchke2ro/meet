<?php
/**
 * @var $model PostCreate
 */

use app\modules\admin\models\forms\PostCreate;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Bejegyzés létrehozása';
?>


<div class="row">
	<div class="col-12">
		<div class="card">
			<?php $form = ActiveForm::begin(['id' => 'form-post-create', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'title')->label('Cím')->textInput()?>
				<?=$form->field($model, 'intro')->label('Bevezető')->textarea(['rows' => 10])?>
				<?=$form->field($model, 'text')->label('Szöveg')->textarea(['rows' => 10, 'class' =>'form-control ckeditor', 'data-upload-type' => 'post'])?>
				<?=$form->field($model, 'order')->label('Sorrend')->textInput(['type' => 'number'])?>
				<?=$form->field($model, 'date')->label('Dátum')->textInput(['type' => 'date'])?>
				<?=$form->field($model, 'tags')->label('Címkék')->hint('<p class="small p-2">'.implode(', ', $allTags).'</p>')->textarea(['rows' => 10]);?>
				<?=$form->field($model, 'image')->label('Kép')->fileInput()?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
