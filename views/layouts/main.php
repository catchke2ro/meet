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
<body>
<?php $this->beginBody() ?>

<?php NavBar::begin(['brandLabel' => 'MEET', 'brandUrl' => '/']); ?>

<?php
$menuItems = [
	['label' => 'Főoldal', 'url' => ['/']]
];
if (Yii::$app->user->isGuest) {
	$menuItems[] = ['label' => 'Belépés', 'url' => ['/user/login']];
	$menuItems[] = ['label' => 'Regisztráció', 'url' => ['/user/registration']];
} else {
	$menuItems[] = Html::beginForm(['/user/logout'], 'post', ['id' => 'logoutForm']) . Html::endForm();
	$menuItems[] = ['label' => 'Kérdések', 'url' => ['/question/index']];
	$menuItems[] = ['label' => 'Kilépés', 'url' => 'javascript:void(0)', 'linkOptions' => ['class' => 'logoutLink']];
}
?>
<?=Nav::widget([
	'items' => $menuItems
]);?>

<?php NavBar::end();?>

<div class="container-fluid">
	<?=$content;?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
