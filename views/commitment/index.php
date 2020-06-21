<?php

use app\models\CommitmentCategory;
use app\models\UserQuestionFill;

/**
 * @var $commitmentCategories                     CommitmentCategory[]
 * @var $questionFill                             UserQuestionFill[]
 * @var $this                                     yii\web\View
 * @var $checkedCommitmentOptions                 array
 */

$this->title = 'Vállalások';

?>

<form method="post" action="">
	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	<div class="accordion" id="commitmentsAccordion">
		<?php
		foreach ($commitmentCategories as $commitmentCategory) {
			echo $this->render('category', [
				'commitmentCategory'       => $commitmentCategory,
				'questionFill'             => $questionFill,
				'checkedCommitmentOptions' => $checkedCommitmentOptions
			]);
		}
		?>
	</div>

	<button type="submit" class="btn btn-primary">Submit</button>
</form>


