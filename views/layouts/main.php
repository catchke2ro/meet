<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);
$lang = Yii::$app->language;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?=$lang?>">
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
			<div class="right">
				<?php if($lang === 'hu-HU') { ?>
					<?php if (!Yii::$app->getUser()->isGuest) { ?>
						<div class="user">
							<p class="text-small loggedInName">Belépve: <span><?=Yii::$app->getUser()->getIdentity()->name;?></span></p>
							<?php echo Html::beginForm(['/user/logout'], 'post', ['id' => 'logoutForm']) . Html::endForm(); ?>
							<a href="javascript:void(0)" class="btn btn-sm btn-primary logoutLink">Kilépés</a>
						</div>
					<?php } else { ?>
						<div class="user">
							<p class="text-small notLoggedInName">Ön nincs bejelentkezve!</p>
							<a href="/belepes" class="btn btn-sm btn-primary">Belépés / Regisztráció</a>
						</div>
					<?php } ?>
				<?php } ?>
				<div class="langSelector">
					<?php if($lang !== 'hu-HU') { ?>
						<a href="/"><img src="/assets/img/hu.png" alt="Magyar"/></a>
					<?php } else { ?>
						<a href="/en"><img src="/assets/img/en.png" alt="English"/></a>
					<?php } ?>
				</div>
			</div>
		</div>

		<?php if ($lang === 'hu-HU') {?>
			<nav class="main-header navbar navbar-expand-md navbar-white navbar-light">

				<!-- Collapse button -->
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menü">
					<span class="fa fa-bars"></span> <strong>Menü</strong>
				</button>

				<div class="collapse navbar-collapse" id="mainNav">
					<!-- Left navbar links -->
					<ul class="navbar-nav">
						<li class="nav-item">
							<a href="/programleiras" class="nav-link <?=menuActiveClass('/programleiras');?>">Programleírás</a>
						</li>
						<li class="nav-item">
							<a href="/dokumentumok" class="nav-link <?=menuActiveClass('/dokumentumok');?>">Dokumentumok</a>
						</li>
						<li class="nav-item">
							<a href="/modulok" class="nav-link <?=menuActiveClass('/modulok');?>">Modulok</a>
						</li>
						<li class="nav-item">
							<a href="/aktivitas" class="nav-link <?=menuActiveClass('/aktivitas');?>">Aktivitás</a>
						</li>
						<li class="nav-item">
							<a href="/#resztvevok" class="nav-link">Résztvevők</a>
						</li>
						<li class="nav-item">
							<a href="/#kapcsolat" class="nav-link">Kapcsolat</a>
						</li>

						<?php if (!Yii::$app->user->isGuest) { ?>
							<li class="nav-item d-none d-sm-inline-block loggedInMenu">
								<a href="/vallalasok" class="nav-link <?=menuActiveClass('/vallalasok');?>">Vállalás</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			</nav>
		<?php } ?>
	</header>



	<div class="content-wrapper container-fluid <?=$this->params['pageClass'] ?? '';?>">
		<?php include __DIR__.'/flash-messages.php';?>
		<?=$content;?>
	</div>

	<footer>
		<div class="mailchimp">
			<h4>Feliratkozás az Ararát hírlevelére</h4>
			<p>Ha szeretnél elsők között értesülni a Magyarországi Evangélikus Egyház teremtésvédelmi tevékenységéről (híreiről, eseményeiről, jógyakorlatairól), akkor iratkozz fel az Ararát hírlevelére a „Feliratkozás” gomb megnyomásával!</p>
			<p>A feliratkozással hozzájárulsz személyes adataid kezeléséhez, amelyet a <a href="https://zsinat.lutheran.hu/torvenyek/toervenyek/4-2018.-viii.-28.-orszagos-szabalyrendelet-a-magyarorszagi-evangelikus-egyhaz-adatvedelmi-es-adatbiztonsagi-szabalyzatarol-melleklet/B5_MEE%20adatvedelmi%20szabalyzata_20180626.pdf/view" target="_blank">Magyarországi Evangélikus Egyház 4/2018. (VIII. 28.) országos szabályrendeletében</a> foglalt adatvédelmi és adatbiztonsági szabályzat határoz meg.</p>

			<div id="mc_embed_signup">
				<form action="https://arterm.us7.list-manage.com/subscribe/post?u=ef0e244034a79c398fbf3da29&amp;id=1efc6b60bd" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<div id="mc_embed_signup_scroll">

						<div class="mc-field-group form-group">
							<input type="email" value="" name="EMAIL" class="required email form-control" id="mce-EMAIL" placeholder="E-mail cím" autocomplete="off">
						</div>
						<div id="mce-responses" class="clear">
							<div class="response" id="mce-error-response" style="display:none"></div>
							<div class="response" id="mce-success-response" style="display:none"></div>
						</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
						<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_ef0e244034a79c398fbf3da29_1efc6b60bd" tabindex="-1" value=""></div>
						<div class="clear"><input type="submit" value="Feliratkozás" name="subscribe" id="mc-embedded-subscribe" class="button btn btn-secondary"></div>
					</div>
				</form>
			</div>
		</div>
		<p><span>Copyright @2020</span>&nbsp;|&nbsp;<a href="/aef">ÁEF</a>&nbsp;|&nbsp;<a href="/impresszum">Impresszum</a></p>
	</footer>

</div>
<?php $this->endBody() ?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-177173686-1"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-177173686-1');
</script>

</body>
</html>
<?php $this->endPage() ?>
