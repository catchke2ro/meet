<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
	<meta charset="<?=Yii::$app->charset?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php $this->registerCsrfMetaTags() ?>
	<title><?=Html::encode($this->title)?></title>
	<?php $this->head() ?>
</head>
<body class="hold-transition layout-fixed layout-top-nav">
<?php $this->beginBody() ?>

<div class="wrapper">

	<nav class="main-header navbar navbar-expand navbar-white navbar-light">
		<!-- Left navbar links -->
		<ul class="navbar-nav">
			<li class="nav-item d-none d-sm-inline-block"><a href="/" class="nav-link">Főoldal</a></li>
			<?php if (Yii::$app->user->isGuest) { ?>
				<li class="nav-item"><a href="/user/login" class="nav-link">Belépés</a></li>
				<li class="nav-item"><a href="/user/registration" class="nav-link">Regisztráció</a></li>
			<?php } else { ?>
				<?php Html::beginForm(['/user/logout'], 'post', ['id' => 'logoutForm']) . Html::endForm(); ?>
				<li class="nav-item"><a href="/question/index" class="nav-link">Kérdések</a></li>
				<li class="nav-item"><a href="javascript:void(0)" class="nav-link logoutLink">Kilépés</a></li>
			<?php } ?>

		</ul>
	</nav>

	<div class="content-wrapper container-fluid">
		<?=$content;?>
	</div>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
