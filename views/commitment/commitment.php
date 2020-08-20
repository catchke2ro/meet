<?php

use app\models\CommitmentCategory;
use app\models\CommitmentItem;
use app\models\CommitmentOption;
use app\models\QuestionInstance;
use app\models\UserCommitmentFill;use app\models\UserQuestionFill;

/**
 * @var $fill                                     UserQuestionFill
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
	<p class="mb-0 qcTitle"><?=$commitment->name;?></p>
	<div class="description"><?=$commitment->description;?></div>
	<div class="row">
		<div class="col-md-6">
			<div class="optionsWrapper">
				<div class="form-group options">
					<?php $customInputOptionId = null; ?>
					<?php foreach ($commitment->options as $key => $option) { ?>
						<?php if ($option->is_custom_input) {
							$customInputOptionId = $option->id;
						} ?>
						<div class="icheck-greensea">
							<input type="radio"
								   class="commitmentOption qcOption"
								   data-id="<?=$option->id;?>"
								   data-qid="<?=$commitment->id;?>"
								   value="<?=$option->id;?>"
								   name="options[<?=$commitment->id;?>][<?=$instanceNumber;?>]"
								   id="opt<?=$commitment->id?>_<?=$option->id?>_<?=$instanceNumber;?>"
								   data-custominput="<?=$option->is_custom_input ? 1 : 0;?>"
								<?=in_array($option->id, $checkedCommitmentOptions ?: []) ? 'checked' : '';?>
								   autocomplete="off">
							<label for="opt<?=$commitment->id?>_<?=$option->id?>_<?=$instanceNumber;?>"
								   data-toggle="tooltip"
								   data-placement="right"
								   title="<?=$option->description;?>"
							><?=$option->name;?></label>
						</div>
					<?php } ?>
				</div>
				<?php if ($customInputOptionId) { ?>
					<div class="form-group customInput <?=$commitment->isOnlyCustomInput() ? '' : 'd-none';?>"
						 data-optionid="<?=$customInputOptionId;?>">
						<textarea name="customInputs[<?=$commitment->id;?>][<?=$customInputOptionId;?>][<?=$instanceNumber;?>]" placeholder="Egyéni szöveg"
								  class="form-control"><?php
							echo $commitment->getCustomInputValue(Yii::$app->request, $fill);
							?></textarea>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="col-md-6">
			<?php if ($commitment->month_step) { ?>
				<div class="commitmentInterval form-group">
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text">Vállalás időtartama:</span>
						</div>
						<input type="hidden"
							   name="intervalMultipliers[<?=$commitment->id;?>][<?=$instanceNumber;?>]"
							   value="<?=getIntervalMultiplier($commitment->month_step);?>"/>
						<input type="number"
							   class="form-control"
							   name="intervals[<?=$commitment->id;?>][<?=$instanceNumber;?>]"
							   step="<?=getIntervalStep($commitment->month_step);?>"
							   min="<?=getIntervalThreshold($commitment->months_min, $commitment->month_step);?>"
							   max="<?=getIntervalThreshold($commitment->months_max, $commitment->month_step);?>"
							   value="<?=$fill ? convertIntervalValue($fill->getIntervalValue($commitment, $instanceNumber), $commitment->month_step) : null;?>" />
						<div class="input-group-append">
							<span class="input-group-text"><?=getIntervalName($commitment->month_step);?></span>
						</div>
					</div>
				</div>
			<?php } ?>

			<?php if ($fill instanceof UserCommitmentFill) { ?>
				<a href="javascript:void(0)"
				   class="btn btn-sm btn-secondary"
				   data-toggle="modal"
				   data-target="#historyModal"
				   data-commitmentid="<?=$commitment->id;?>"
				   data-commitment="<?=$commitment->name;?>"
				>Korábbi vállalások</a>
			<?php } ?>
		</div>
	</div>

</div>
