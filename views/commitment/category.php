<?php

use app\models\CommitmentCategory;
use app\models\UserQuestionFill;

/**
 * @var $commitmentCategory                       CommitmentCategory
 * @var $questionFill                             UserQuestionFill
 * @var $this                                     yii\web\View
 * @var $checkedCommitmentOptions                 array
 */

$catId = $commitmentCategory->id;
$hasInstances = $commitmentCategory->has_instances;

$instanceCountRequest = $commitmentCategory->getInstanceCount(Yii::$app->request);
$instanceCountQuestion = $questionFill->getInstanceCountForCategory($commitmentCategory);
$instanceCount = max($instanceCountRequest, $instanceCountQuestion);
?>

<div class="card commitmentCategory <?=$hasInstances ? 'hasInstances' : null;?>"
	 data-category-id="<?=$commitmentCategory->id;?>"
	 data-condition-option="<?=$commitmentCategory->conditionOption ? $commitmentCategory->conditionOption->id : null;?>">

	<div class="card-header">
		<h3 class="card-title" data-toggle="collapse" data-target="#collapseCat<?=$catId;?>">
			<a href="javascript:void(0)"><?=$commitmentCategory->name;?></a>
		</h3>
		<h6 class="card-subtitle mb-2 text-muted"><?=$commitmentCategory->description;?></h6>
		<?php if ($hasInstances) { ?>
			<input class="instanceNumber" type="number" step="1" min="1" value="<?=$instanceCount;?>" />
		<?php } ?>
	</div>
	<div id="collapseCat<?=$catId;?>" class="collapse" data-parent="#commitmentsAccordion">
		<div class="card-body">
			<?php for ($inst = 0; $inst < $instanceCount; $inst ++) { ?>
				<?php $instance = $questionFill->getInstance($commitmentCategory, $inst); ?>
				<div class="card categoryInstance" data-instance="<?=$inst;?>">
					<div class="card-body">
						<?php if ($hasInstances) { ?>
							<div class="instanceTitle">
								<input class="instanceName" type="text" name="instanceNames[<?=$commitmentCategory->id;?>][<?=$inst;?>]"
									   value="<?=$instance ? $instance->name : null;?>" />
							</div>
						<?php } ?>
						<?php foreach ($commitmentCategory->items as $commitment) { ?>
							<?=$this->render('commitment', [
								'commitment'               => $commitment,
								'instanceNumber'           => $inst,
								'instance'                 => $instance,
								'questionFill'             => $questionFill,
								'checkedCommitmentOptions' => $checkedCommitmentOptions
							]);?>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
