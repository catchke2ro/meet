<?php

use app\models\CommitmentCategory;
use app\models\UserQuestionFill;

/**
 * @var $commitmentCategories                     CommitmentCategory[]
 * @var $fill                                     UserQuestionFill[]
 * @var $this                                     yii\web\View
 * @var $checkedCommitmentOptions                 array
 */

$this->title = 'Vállalások';

?>

<form method="post" action="" class="commitmentsForm">
	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	<div class="card">
		<div class="card-header bg-primary">
			Kiértékelés
		</div>
		<div class="card-body">
			<div class="commitmentScore">
				<p>Pontszám: <span class="score"></span></p>
				<p>Összpontszám: <span class="scoreWithSpecial"></span></p>
				<p>Aktuális szint: <span class="currentLevel"></span></p>
				<p>Következő szint: <span class="nextLevel"></span></p>
				<p>Százalék a következő szintig: <span class="nextLevelPercentage"></span>%</p>
			</div>
		</div>
	</div>

	<div class="accordion treeAccordion mt-5 mb-5" id="commitmentsAccordion">
		<?php
		foreach ($commitmentCategories as $commitmentCategory) {
			echo $this->render('category', [
				'commitmentCategory'       => $commitmentCategory,
				'fill'             => $fill,
				'checkedCommitmentOptions' => $checkedCommitmentOptions
			]);
		}
		?>
	</div>

	<button type="submit" class="btn btn-primary">Submit</button>
</form>


<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h5 class="modal-title" id="historyModalLabel">Vállalás története:</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-loader d-none">
				<div class="d-flex justify-content-center align-items-center">
					<i class="fa fa-spinner fa-2x fa-spin"></i>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
			</div>
		</div>
	</div>
</div>
