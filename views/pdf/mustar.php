<?php

use meetbase\models\lutheran\Organization;

/**
 * @var $organization Organization
 */

$pastor = $organization->getPastorGeneral() ?: $organization->getPastor();
$superintendent = $organization->getSuperintendent();
$referer = $organization->getMeetReferer();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link href="<?=Yii::$app->getBasePath();?>/web/dist/pdf.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>

<table class="header">
	<tr>
		<td class="logo"><img src="<?=Yii::$app->getBasePath();?>/web/assets/img/modules/meet_fologo_vallalas_mustarmag.png" alt="Mustármag"></td>
		<td class="quote"><p>„Hasonló a mennyek országa a mustármaghoz, amelyet fog az ember, és elvet szántóföldjébe. Ez kisebb ugyan minden magnál, de amikor felnő, nagyobb minden veteménynél, és fává lesz, úgyhogy eljönnek az égi madarak és fészket raknak ágai között.”<br /> (Mt 13,31-32)</p></td>
	</tr>
</table>
<h1><?=$organization->nev;?></h1>
<h2><?=$organization->addressContacts ? reset($organization->addressContacts)->ertek2.', ' : null;?><?=strftime('%Y. %B %e.');?></h2>

<br />

<div class="content">
	<p>Közösségünk a mai napon csatlakozott a <strong>Műveld és őrizd! Evangélikus Egyházi Teremtésvédelmi programhoz</strong>, mellyel az alábbi teremtésvédelmi vállalást tettük:</p>
	<ul>
		<li>Istentiszteleti alkalmainkon rendszeresen, legalább havonta egyszer imádkozunk a teremtett világért,</li>
		<li>minden évben megünnepeljük a Teremtés hetét,</li>
		<li>megosztjuk jó gyakorlatainkat másokkal is, legalább évente egyszer.</li>
	</ul>

	<p>Vállaljuk továbbá, hogy a Műveld és őrizd! Evangélikus Egyházi Teremtésvédelmi programot saját közösségi felületeinken és csatornáinkon keresztül hirdetjük más közösségek számára is.</p>
	<p>Közösségünk a MEET program Mustármag modul arculati elemeinek használatára jogosult, azokat felületeinken elhelyezhetjük.</p>
	<p>Isten áldása legyen közösségünk munkáján!</p>
</div>


<table class="signs">
	<tr>
		<td>
			<div class="signBlock">
				<span class="dots"></span>
				<span class="name"><?=$pastor ? $pastor->nev : '&nbsp;';?></span><br />
				<span class="position">Lelkész</span>
			</div>
		</td>
		<td>
			<div class="signBlock">
				<span class="dots"></span>
				<span class="name"><?=$superintendent ? $superintendent->nev : '&nbsp;';?></span><br />
				<span class="position">Felügyelő</span>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="stamp"><br /><br />Ph. <br /><br /><br /><br /><br /><br /></td>
	</tr>
	<tr>
		<td colspan="2">
			<div class="signBlock">
				<span class="dots"></span>
				<span class="name"><?=$referer ? $referer->nev : '&nbsp;';?></span><br />
				<span class="position">MEET referens</span>
			</div>
		</td>
	</tr>
</table>
</body>
</html>