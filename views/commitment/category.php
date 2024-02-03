<?php

use app\models\CommitmentCategory;
use app\models\interfaces\FillInterface;

/**
 * @var $commitmentCategory                       CommitmentCategory
 * @var $fill                                     FillInterface
 * @var $this                                     yii\web\View
 * @var $checkedCommitmentOptions                 array
 */

$catId = $commitmentCategory->id;
$hasInstances = $commitmentCategory->hasInstances;

$instanceCountRequest = $commitmentCategory->getInstanceCount(Yii::$app->request);
$instanceCountQuestion = $fill ? $fill->getInstanceCountForCategory($commitmentCategory) : 1;
$instanceCount = max($instanceCountRequest, $instanceCountQuestion);
?>

<div class="card card-primary qcCategory commitmentCategory <?=$hasInstances ? 'hasInstances' : null;?>"
	 data-category-id="<?=$commitmentCategory->id;?>"
	 data-condition-option="<?=$commitmentCategory->conditionOption ? $commitmentCategory->conditionOption->id : null;?>">

	<div class="card-header collapsed" data-toggle="collapse" data-target="#collapseCat<?=$catId;?>">
		<div class="arrowIcons">
			<span class="fa fa-chevron-circle-down down"></span>
			<span class="fa fa-chevron-circle-up up"></span>
		</div>
		<div class="titles">
			<h3 class="card-title">
				<a href="javascript:void(0)"><?=$commitmentCategory->name;?></a>
			</h3>
			<h6 class="card-subtitle mb-2"><?=$commitmentCategory->description;?></h6>
		</div>
		<?php if ($hasInstances) { ?>
			<div class="instanceNumberWrapper">
				<label>Elemek száma</label>
				<input class="instanceNumber" type="number" step="1" min="1" value="<?=$instanceCount;?>" />
			</div>
		<?php } ?>
	</div>
	<div id="collapseCat<?=$catId;?>" class="collapse card-body-wrapper" data-parent="#commitmentsAccordion">
		<div class="card-body">
			<?php for ($inst = 0; $inst < $instanceCount; $inst ++) { ?>
				<?php $instance = $fill ? $fill->getInstance($commitmentCategory, $inst) : null; ?>
				<div class="card categoryInstance" data-instance="<?=$inst;?>">
					<?php if ($hasInstances) { ?>
						<div class="card-header">
							<div class="instanceTitle">
								<input class="instanceName" type="text" name="instanceNames[<?=$commitmentCategory->id;?>][<?=$inst;?>]"
									   value="<?=$instance ? $instance->name : null;?>" placeholder="Elem neve" />
							</div>
						</div>
					<?php } ?>
					<div class="card-body">
						<?php foreach ($commitmentCategory->items as $commitment) { ?>
							<?php if (!$commitment->is_active) {
								continue;
							} ?>
							<?=$this->render('commitment', [
								'commitment'               => $commitment,
								'instanceNumber'           => $inst,
								'instance'                 => $instance,
								'fill'                     => $fill,
								'checkedCommitmentOptions' => $checkedCommitmentOptions
							]);?>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
