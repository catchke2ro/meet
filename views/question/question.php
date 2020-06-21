<?php

use app\models\QuestionItem;
use app\models\QuestionOption;

/**
 * @var $question QuestionItem
 * @var $this     yii\web\View
 * @var $option   QuestionOption
 * @var $instance int
 */

$catId = $questionCategory->id;
?>

<div class="question pb-2 <?=$question->getCssClass();?>">
	<p class="mb-0"><?=$question->name;?></p>
	<div class="description"><?=$question->description;?></div>
	<div class="options">
		<div class="form-group options">
			<?php $customInputOptionId = null; ?>
			<?php foreach ($question->options as $key => $option) { ?>
				<?php if ($option->is_custom_input) {
					$customInputOptionId = $option->id;
				} ?>
				<div class="icheck-greensea">
					<input type="radio"
						   class="questionOption qcOption"
						   data-id="<?=$option->id;?>"
						   data-qid="<?=$question->id;?>"
						   value="<?=$option->id;?>"
						   name="options[<?=$question->id;?>][<?=$instance;?>]"
						   id="opt<?=$question->id?>_<?=$option->id?>_<?=$instance;?>"
						   data-custominput="<?=$option->is_custom_input ? 1 : 0;?>"
						<?=$option->isChecked(Yii::$app->request, $instance) ? 'checked' : '';?>
						   autocomplete="off">
					<label for="opt<?=$question->id?>_<?=$option->id?>_<?=$instance;?>"
						   data-toggle="tooltip"
						   data-placement="right"
						   title="<?=$option->description;?>"
					><?=$option->name;?></label>
				</div>
			<?php } ?>
		</div>
		<?php if ($customInputOptionId) { ?>
			<div class="form-group customInput <?=$question->isOnlyCustomInput() ? '' : 'd-none';?>" data-optionid="<?=$customInputOptionId;?>">
				<textarea name="customInputs[<?=$question->id;?>][<?=$customInputOptionId;?>][<?=$instance;?>]" placeholder="Egyéni szöveg" class="form-control"><?php
					echo $question->getCustomInputValue(Yii::$app->request);
				?></textarea>
			</div>
		<?php } ?>

	</div>
</div>
