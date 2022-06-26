<?php

/* @var $this \yii\web\View */

/* @var $content string */

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
	<meta http-equiv="x-ua-compatible" content="ie=edge">

	<?php $this->registerCsrfMetaTags() ?>
	<title><?=Html::encode($this->title)?></title>
	<?php $this->head() ?>
	<script type="text/javascript" src="/dist/admin.js"></script>
	<link rel="stylesheet" href="/dist/admin.css"/>
</head>
<body class="hold-transition sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper">
	<aside class="main-sidebar sidebar-dark-primary elevation-4">
		<a href="index3.html" class="brand-link">
			<img src="/assets/img/meet_logo_webre_fektetett.png" alt="AdminLTE Logo" class="brand-image"/>
			<span class="brand-text font-weight-light">MEETAdmin</span>
		</a>

		<div class="sidebar">
			<div class="user-panel mt-3 pb-3 mb-3 d-flex">
				<div class="info"><?=Yii::$app->getUser()->getIdentity()->name;?></div>
			</div>

			<nav class="mt-2">
				<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
					<li class="nav-item">
						<a href="/meet/modules" class="nav-link">
							<i class="fa fa-database nav-icon"></i>
							<p>Modulok</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="/meet/posts" class="nav-link">
							<i class="fa fa-newspaper-o nav-icon"></i>
							<p>Bejegyzések</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="/meet/question-categories" class="nav-link">
							<i class="fa fa-question nav-icon"></i>
							<p>Kérdések</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="/meet/commitment-categories" class="nav-link">
							<i class="fa fa-exclamation nav-icon"></i>
							<p>Vállalások</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="/meet/org-commitments" class="nav-link">
							<i class="fa fa-circle nav-icon"></i>
							<p>Kitöltött vállalások</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="/meet/users" class="nav-link">
							<i class="fa fa-user nav-icon"></i>
							<p>Felhasználók</p>
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</aside>

	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark"><?=$this->title;?></h1>
					</div>
				</div>
			</div>
		</div>

		<div class="content">
			<div class="container-fluid">
				<?php $this->render('//layouts/flash-messages');?>
				<?=$content;?>
			</div>
		</div>
	</div>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

