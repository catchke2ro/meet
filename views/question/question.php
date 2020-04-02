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
		<div class="btn-group btn-group-toggle" data-toggle="buttons">
			<?php $customInputOptionId = null; ?>
			<?php foreach ($question->options as $key => $option) { ?>
				<?php if ($option->is_custom_input) {
					$customInputOptionId = $option->id;
				} ?>
				<label class="btn btn-secondary"
					   data-toggle="tooltip"
					   data-placement="bottom"
					   title="<?=$option->description;?>">
					<input type="radio"
						   class="questionOption"
						   data-id="<?=$option->id;?>"
						   value="1"
						   name="options[<?=$question->id;?>][<?=$option->id;?>][<?=$instance;?>]"
						   id="opt<?=$question->id?>_<?=$option->id?>_<?=$instance;?>"
						<?=$option->isChecked(Yii::$app->request, $instance) ? 'checked' : '';?>
						   autocomplete="off">
					<?=$option->name;?>
				</label>
			<?php } ?>
		</div>
		<?php if ($customInputOptionId) { ?>
			<div class="customInput <?=$question->isOnlyCustomInput() ? '' : 'd-none';?>">
			<textarea name="customInputs[<?=$question->id;?>][<?=$customInputOptionId;?>][<?=$instance;?>]" placeholder="Egyéni szöveg">
				<?=$question->getCustomInputValue(Yii::$app->request);?>
			</textarea>
			</div>
		<?php } ?>

	</div>
</div>
