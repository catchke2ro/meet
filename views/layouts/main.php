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
<body class="hold-transition layout-fixed layout-top-nav layout-frontend">
<?php $this->beginBody() ?>

<div class="wrapper">

	<header>
		<div class="headerTop">
			<div class="logo"><img src="/assets/img/meet_logo_webre_fektetett.png" alt="MEET"/></div>
			<?php if (!Yii::$app->getUser()->isGuest) { ?>
				<div class="user">
					<p class="text-small loggedInName">Belépve: <?=Yii::$app->getUser()->getIdentity()->name;?></p>
					<?php echo Html::beginForm(['/user/logout'], 'post', ['id' => 'logoutForm']) . Html::endForm(); ?>
					<a href="javascript:void(0)" class="btn btn-sm btn-primary logoutLink">Kilépés</a>
				</div>
			<?php } ?>
		</div>

		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item d-none d-sm-inline-block"><a href="/" class="nav-link">Főoldal</a></li>
				<?php if (Yii::$app->user->isGuest) { ?>
					<li class="nav-item"><a href="/belepes" class="nav-link">Belépés</a></li>
					<li class="nav-item"><a href="/regisztracio" class="nav-link">Regisztráció</a></li>
				<?php } else { ?>
					<li class="nav-item"><a href="/vallalasok" class="nav-link">Vállalás</a></li>
				<?php } ?>
			</ul>
		</nav>
	</header>



	<div class="content-wrapper container-fluid <?=$this->params['pageClass'] ?? '';?>">
		<?php include __DIR__.'/flash-messages.php';?>
		<?=$content;?>
	</div>

	<footer>
		<p><span>Copyright @2020</span>&nbsp;|&nbsp;<a href="/">Impresszum</a></p>
	</footer>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
