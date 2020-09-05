<?php

use app\models\QuestionCategory;

/**
 * @var $questionCategory QuestionCategory
 * @var $this             yii\web\View
 * @var $instanceCount    int
 */

$catId = $questionCategory->id;
$hasInstances = $questionCategory->has_instances;
?>

<div class="card card-primary qcCategory questionCategory <?=$hasInstances ? 'hasInstances' : null;?>"
	 data-category-id="<?=$questionCategory->id;?>"
	 data-condition-option="<?=$questionCategory->conditionOption ? $questionCategory->conditionOption->id : null;?>">

	<div class="card-header collapsed" data-toggle="collapse" data-target="#collapseCat<?=$catId;?>">
		<div class="arrowIcons">
			<span class="fa fa-chevron-circle-down down"></span>
			<span class="fa fa-chevron-circle-up up"></span>
		</div>
		<div class="titles">
			<h3 class="card-title">
				<a href="javascript:void(0)"><?=$questionCategory->name;?></a>
			</h3>
			<h6 class="card-subtitle mb-2"><?=$questionCategory->description;?></h6>
		</div>
		<?php if ($hasInstances) { ?>
			<div class="instanceNumberWrapper">
				<label>Elemek száma</label>
				<input class="instanceNumber" type="number" step="1" min="1" value="<?=$instanceCount;?>" />
			</div>
		<?php } ?>
	</div>
	<div id="collapseCat<?=$catId;?>" class="collapse card-body-wrapper" data-parent="#questionsAccordion">
		<div class="card-body">
			<?php for ($inst = 0; $inst < $instanceCount; $inst ++) { ?>
				<div class="card categoryInstance" data-instance="<?=$inst;?>">
					<?php if ($hasInstances) { ?>
						<div class="card-header">
							<div class="instanceTitle">
								<input class="instanceName" type="text" name="instanceNames[<?=$questionCategory->id;?>][<?=$inst;?>]" placeholder="Elem neve" />
							</div>
						</div>
					<?php } ?>
					<div class="card-body">
						<?php foreach ($questionCategory->items as $question) { ?>
							<?php if (!$question->is_active) {
								continue;
							} ?>
							<?=$this->render('question', ['question' => $question, 'instance' => $inst]);?>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
