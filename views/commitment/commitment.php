<?php

use app\models\CommitmentCategory;
use app\models\CommitmentItem;
use app\models\CommitmentOption;
use app\models\QuestionInstance;
use app\models\UserQuestionFill;

/**
 * @var $questionFill                             UserQuestionFill
 * @var $commitmentCategory                       CommitmentCategory
 * @var $commitment                               CommitmentItem
 * @var $this                                     yii\web\View
 * @var $option                                   CommitmentOption
 * @var $instanceNumber                           int
 * @var $instance                                 QuestionInstance|null
 * @var $checkedCommitmentOptions                 array
 */

$catId = $commitmentCategory->id;
?>

<div class="commitment pb-2 <?=$commitment->getCssClass();?>">
	<p class="mb-0"><?=$commitment->name;?></p>
	<div class="description"><?=$commitment->description;?></div>
	<div class="options">
		<div class="btn-group btn-group-toggle" data-toggle="buttons">
			<?php $customInputOptionId = null; ?>
			<?php foreach ($commitment->options as $key => $option) { ?>
				<?php if ($option->is_custom_input) {
					$customInputOptionId = $option->id;
				} ?>
				<label class="btn btn-secondary"
					   data-toggle="tooltip"
					   data-placement="bottom"
					   title="<?=$option->description;?>">
					<input type="radio"
						   class="commitmentOption"
						   data-id="<?=$option->id;?>"
						   data-qid="<?=$commitment->id;?>"
						   value="1"
						   name="options[<?=$commitment->id;?>][<?=$option->id;?>][<?=$instanceNumber;?>]"
						   id="opt<?=$commitment->id?>_<?=$option->id?>_<?=$instanceNumber;?>"
						   data-custominput="<?=$option->is_custom_input ? 1 : 0;?>"
						<?=in_array($option->id, $checkedCommitmentOptions) ? 'checked' : '';?>
						   autocomplete="off">
					<?=$option->name;?>
				</label>
			<?php } ?>
		</div>
		<?php if($commitment->month_step) { ?>
			<div class="commitmentInterval">
				<input type="number"
					   name="intervals[<?=$commitment->id;?>][<?=$instanceNumber;?>]"
					   step="<?=$commitment->month_step;?>"
					   min="<?=$commitment->months_min;?>"
					   max="<?=$commitment->months_max;?>"
					   value="<?=$commitment->getIntervalValue(Yii::$app->request, $instanceNumber);?>"
				/><span>hónap</span>
			</div>
		<?php } ?>
		<div>

		</div>
		<?php if ($customInputOptionId) { ?>
			<div class="customInput <?=$commitment->isOnlyCustomInput() ? '' : 'd-none';?>" data-optionid="<?=$customInputOptionId;?>">
			<textarea name="customInputs[<?=$commitment->id;?>][<?=$customInputOptionId;?>][<?=$instanceNumber;?>]" placeholder="Egyéni szöveg">
				<?=$commitment->getCustomInputValue(Yii::$app->request);?>
			</textarea>
			</div>
		<?php } ?>

	</div>
</div>
