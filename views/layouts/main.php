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
	<title><?=Html::encode($this->title)?> | MEET</title>

	<link rel="apple-touch-icon" sizes="57x57" href="/icons/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/icons/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/icons/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/icons/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/icons/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/icons/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/icons/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/icons/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/icons/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/icons/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16.png">
	<link rel="manifest" href="/icons/manifest.json">
	<meta name="msapplication-TileColor" content="#6dc067">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#6dc067">


	<?php $this->head() ?>
</head>
<body class="hold-transition layout-fixed layout-top-nav layout-frontend">
<?php $this->beginBody() ?>

<div class="wrapper">

	<header>
		<div class="headerTop">
			<div class="logo"><a href="/"><img src="/assets/img/meet_logo_webre_fektetett.png" alt="MEET"/></a></div>
			<?php if (!Yii::$app->getUser()->isGuest) { ?>
				<div class="user">
					<p class="text-small loggedInName">Belépve: <span><?=Yii::$app->getUser()->getIdentity()->name;?></span></p>
					<?php echo Html::beginForm(['/user/logout'], 'post', ['id' => 'logoutForm']) . Html::endForm(); ?>
					<a href="javascript:void(0)" class="btn btn-sm btn-primary logoutLink">Kilépés</a>
				</div>
			<?php } else { ?>
				<div class="user">
					<p class="text-small notLoggedInName">Ön nincs bejelentkezve!</p>
					<a href="/belepes" class="btn btn-sm btn-primary">Belépés</a>
				</div>
			<?php } ?>
		</div>

		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item d-none d-sm-inline-block">
					<a href="/programleiras" class="nav-link <?=menuActiveClass('/programleiras');?>">Programleírás</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="/modulok" class="nav-link <?=menuActiveClass('/modulok');?>">Modulok</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="/aktivitas" class="nav-link <?=menuActiveClass('/aktivitas');?>">Aktivitás</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="/#resztvevok" class="nav-link">Résztvevők</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="/#kapcsolat" class="nav-link">Kapcsolat</a>
				</li>

				<?php if (!Yii::$app->user->isGuest) { ?>
					<li class="nav-item d-none d-sm-inline-block loggedInMenu">
						<a href="/vallalasok" class="nav-link <?=menuActiveClass('/vallalasok');?>">Vállalás</a>
					</li>
				<?php } ?>
			</ul>
		</nav>
	</header>



	<div class="content-wrapper container-fluid <?=$this->params['pageClass'] ?? '';?>">
		<?php include __DIR__.'/flash-messages.php';?>
		<?=$content;?>
	</div>

	<footer>
		<p><span>Copyright @2020</span>&nbsp;|&nbsp;<a href="/impresszum">Impresszum</a></p>
	</footer>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
