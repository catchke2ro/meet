<?php

use app\models\AdminOrganization;use app\models\QuestionCategory;
use app\models\User;

/**
 * @var $questionCategories QuestionCategory[]
 * @var $this               yii\web\View
 * @var $user               User
 */

$this->title = 'Kérdések';

?>

<?=$this->render('/parts/admin-changer', [
	'user' => $user,
]);?>

<form method="post" action="" novalidate>
	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	<input type="hidden" name="orgType" value="<?=Yii::$app->user->getIdentity()->getOrgTypeId();?>" />

	<div class="accordion treeAccordion mt-5 mb-5" id="questionsAccordion">
		<?php
		foreach ($questionCategories as $questionCategory) {
			echo $this->render('category', [
				'instanceCount' => $questionCategory->getInstanceCount(Yii::$app->request),
				'questionCategory' => $questionCategory
			]);
		}
		?>
	</div>

	<?php if (!($user->getOrganization() instanceof AdminOrganization)) {?>
		<div class="text-center">
			<button type="submit" class="btn btn-secondary">Tovább</button>
		</div>
	<?php } ?>

</form>


