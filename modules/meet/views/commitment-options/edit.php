<?php

/**
 * @var $model CommitmentOptionCreate
 * @var $item CommitmentItem
 */

use app\modules\meet\models\forms\CommitmentOptionCreate;
use app\modules\meet\models\CommitmentCategory;
use app\modules\meet\models\CommitmentItem;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Vállalás opció módosítása';
?>


<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h6>Kategória: <?=$item->category->name;?></h6>
				<h6>Vállalás: <?=$item->name;?></h6>
			</div>
			<?php $form = ActiveForm::begin(['id' => 'form-commitment-option-create', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'name')->label('Név')->textInput()?>
				<?=$form->field($model, 'description')->label('Leírás')->textarea()?>
				<?=$form->field($model, 'order')->label('Sorrend')->textInput(['type' => 'number'])?>
				<?=$form->field($model, 'isCustomInput')->label('Egyedi szöveges válasz', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'score')->label('Pontszám')->textInput(['type' => 'number'])?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
