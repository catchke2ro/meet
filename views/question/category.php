<?php

use app\models\QuestionCategory;

/**
 * @var $questionCategory QuestionCategory
 * @var $this yii\web\View
 * @var $instanceCount int
 */

$catId = $questionCategory->id;
$hasInstances = $questionCategory->has_instances;
?>

<div class="card questionCategory <?=$hasInstances ? 'hasInstances' : null;?>"
	data-category-id="<?=$questionCategory->id;?>"
	data-condition-option="<?=$questionCategory->conditionOption ? $questionCategory->conditionOption->id : null;?>">

	<div class="card-header">
		<h3 class="card-title" data-toggle="collapse" data-target="#collapseCat<?=$catId;?>">
			<a href="javascript:void(0)"><?=$questionCategory->name;?></a>
		</h3>
		<h6 class="card-subtitle mb-2 text-muted"><?=$questionCategory->description;?></h6>
		<?php if ($hasInstances) {?>
			<input class="instanceNumber" type="number" step="1" min="1" value="<?=$instanceCount;?>"/>
		<?php } ?>
	</div>
	<div id="collapseCat<?=$catId;?>" class="collapse" data-parent="#questionsAccordion">
		<div class="card-body">
			<?php for ($inst = 0; $inst < $instanceCount; $inst ++) { ?>
				<div class="card categoryInstance" data-instance="<?=$inst;?>">
					<div class="card-body">
						<div class="instanceTitle"></div>
						<?php foreach ($questionCategory->items as $question) { ?>
							<?=$this->render('question', ['question' => $question, 'instance' => $inst]);?>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
