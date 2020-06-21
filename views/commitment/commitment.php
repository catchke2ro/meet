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
	<div class="row">
		<div class="col-md-6">
			<div class="options">
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
								<?=in_array($option->id, $checkedCommitmentOptions) ? 'checked' : '';?>
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
					<div class="form-group customInput <?=$commitment->isOnlyCustomInput() ? '' : 'd-none';?>" data-optionid="<?=$customInputOptionId;?>">
						<textarea name="customInputs[<?=$commitment->id;?>][<?=$customInputOptionId;?>][<?=$instance;?>]" placeholder="Egyéni szöveg" class="form-control"><?php
							echo $commitment->getCustomInputValue(Yii::$app->request);
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
						<input type="number"
							   class="form-control"
							   name="intervals[<?=$commitment->id;?>][<?=$instanceNumber;?>]"
							   step="<?=$commitment->month_step;?>"
							   min="<?=$commitment->months_min;?>"
							   max="<?=$commitment->months_max;?>"
							   value="<?=$commitment->getIntervalValue(Yii::$app->request, $instanceNumber);?>"/>
						<div class="input-group-append">
							<span class="input-group-text">hónap</span>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>

</div>
