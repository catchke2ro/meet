<?php
/**
 * @var $this    \yii\web\View
 * @var $fill    OrgCommitmentFill
 * @var $options OrgCommitmentOption[]
 * @var $model   OrgCommitmentEdit
 * @var $modules array
 */

use app\lib\OrgTypes;
use app\modules\meet\models\forms\OrgCommitmentEdit;
use app\modules\meet\models\OrganizationType;
use app\modules\meet\models\OrgCommitmentFill;
use app\modules\meet\models\OrgCommitmentOption;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Vállalás megtekintése #' . $fill->id;

?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>Alapadatok</h4>
			</div>
			<div class="card-body">
				<dl class="row">
					<dt class="col-sm-3">Dátum</dt>
					<dd class="col-sm-9"><?=(new DateTime($fill->date))->format('Y. m. d. H:i:s');?></dd>

					<dt class="col-sm-3">Szervezeti egység</dt>
					<dd class="col-sm-9">#<?=$fill->organization->id;?> - <?=$fill->organization->nev;?></dd>

					<dt class="col-sm-3">Szervezet típusa</dt>
					<dd class="col-sm-9"><?=OrganizationType::getList()[$fill->org_type] ?? null;?></dd>

					<dt class="col-sm-3">Cél modul</dt>
					<dd class="col-sm-9"><?=$fill->targetModule ? $fill->targetModule->name : null;?></dd>

					<dt class="col-sm-3">Végpontszám</dt>
					<dd class="col-sm-9"><?=$fill->getScore();?></dd>
				</dl>
			</div>
		</div>
	</div>


	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>Változtatható információk</h4>
			</div>
			<?php $form = ActiveForm::begin(['id' => 'form-org-commitments-edit', 'enableClientScript' => false]); ?>
			<div class="card-body">
				<?=$form->field($model, 'manualScore')->label('Egyedi pontszám')->textInput(['type' => 'number'])?>
				<?=$form->field($model, 'manualModuleId')->label('Egyedi modul')
					->dropDownList($modules, ['prompt' => '-'])?>
				<?=$form->field($model, 'approved')->label('Elfogadva', ['class' => 'custom-control-label'])->checkbox()?>
				<?=$form->field($model, 'comment')->label('Megjegyzés')->textarea()?>
			</div>
			<div class="card-footer">
				<?=Html::submitButton('Mentés', ['class' => 'btn btn-primary', 'name' => 'save-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>

	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>Vállalások</h4>
			</div>
			<div class="card-body">
				<table class="table table-striped table-sm" style="table-layout: fixed">
					<thead>
					<tr>
						<th>Kategória</th>
						<th>Vállalás</th>
						<th>Példány</th>
						<th>Vállalt opció</th>
						<th>Vállalt időtartam</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($options as $option) { ?>
						<?php
						$item = $option->commitmentOption->item;
						$category = $option->commitmentOption->item->category;
						$categoryName = (isset($prevCat) && $prevCat !== $category->id) ? $category->name : null;
						$itemName = (isset($prevItem) && $prevItem) !== $item->id ? $item->name : null;

						$value = $option->commitmentOption->name;
						if ($option->custom_input) {
							$value .= '<br/><i class="text-sm">' . $option->custom_input . '</i>';
						}

						$instanceName = $option->commitmentInstance ? $option->commitmentInstance->name : null;
						?>
						<tr>
							<td class="text-nowrap text-truncate" title="<?=$categoryName;?>"><?=$categoryName;?></td>
							<td class="text-nowrap text-truncate" title="<?=$itemName;?>"><?=$itemName;?></td>
							<td><?=$instanceName;?></td>
							<td class="text-nowrap text-truncate" title="<?=$option->commitmentOption->name;?>"><?=$value;?></td>
							<td><?=$option->months ? sprintf('%d hónap', $option->months) : '';?></td>
						</tr>
						<?php
						$prevCat = $option->commitmentOption->item->category->id;
						$prevItem = $option->commitmentOption->item->id;
						?>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>



