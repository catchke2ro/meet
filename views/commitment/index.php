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

<form method="post" action="" class="commitmentsForm">
	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	<div class="commitmentScore">
		<p>Pontszám:</p>
		<span class="score"></span>
	</div>
	<div class="accordion treeAccordion mt-5 mb-5" id="commitmentsAccordion">
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


