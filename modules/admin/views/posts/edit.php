<?php

/**
 * @var $model PostEdit
 */

use app\modules\admin\models\forms\PostEdit;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Bejegyzés módosítása';
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
				<?=$form->field($model, 'image')->label('Kép cseréje')
					->hint('<div class="w-25"><img src="'.$model->post->getImageUrl().'" class="mw-100"/></div>')
					->fileInput()?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
