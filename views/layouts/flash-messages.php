<?php if (Yii::$app->session->hasFlash('success')): ?>
	<div class="alert alert-success alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?=Yii::$app->session->getFlash('success') ?>
	</div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('error')): ?>
	<div class="alert alert-danger alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<?=Yii::$app->session->getFlash('error') ?>
	</div>
<?php endif; ?>