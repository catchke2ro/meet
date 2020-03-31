<?php

use app\models\QuestionCategory;

/**
 * @var $questionCategories QuestionCategory[]
 * @var $this               yii\web\View
 */

$this->title = 'Kérdések';

?>

<form method="post" action="">
	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	<div class="accordion" id="questionsAccordion">
		<?php
		foreach ($questionCategories as $questionCategory) {
			echo $this->render('category', [
				'instanceCount' => $questionCategory->getInstanceCount(Yii::$app->request),
				'questionCategory' => $questionCategory
			]);
		}
		?>
	</div>

	<button type="submit" class="btn btn-primary">Submit</button>
</form>


