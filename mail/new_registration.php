<?php
/**
 * @var Person $person
 */

use app\models\lutheran\Person;

?>
<p>Kedves <?=$person->nev;?></p>

<p>Tájékoztatunk, hogy sikeresen kezdeményezted felvételedet a Műveld és őrizd! Evangélikus Egyházi Teremtésvédelmi (MEET) program referensei közé. A program tervezői felületéhez való hozzáférési jogot az általad megküldött adatok és dokumentumok alapján ellenőrizzük. <strong>A jóváhagyásról és a belépés lehetőségéről külön e-mailben fogunk értesíteni.</strong></p>

<p>Ez időre türelmedet szeretnénk kérni, és addig is szeretettel ajánljuk, hogy nézz szét honlapunkon, ahol a programmal kapcsolatos hasznos dolgokról, híreinkről és eseményekről is tájékozódhatsz: <a href="<?=Yii::$app->params['email_url'];?>"><?=Yii::$app->params['email_url_display'];?></a><br />
Bármely kérdéseddel fordulj a MEET adminisztráció koordinátorához a <a href="mailto:meet@lutheran.hu">meet@lutheran.hu</a> címre küldött levéllel.</p>

<p>Köszönjük megtisztelő érdeklődésedet, öröm számunkra jelentkezésed!</p>

<p>Üdvözlettel,<br />A MEET Csapat nevében:<br />Koltai Zsuzsi – koordinátor</p>
