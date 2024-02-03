<?php
/**
 * @var Person       $person
 * @var Organization $organization
 * @var View         $this
 */

use app\models\Organization;
use yii\base\View;

?>
<p>Kedves <?=$person->name;?>!</p>

<p>Isten hozott a Műveld és őrizd! Evangélikus Egyházi Teremtésvédelmi (MEET) programban!</p>
<p>Örömmel tájékoztatunk, hogy regisztrációd véglegesítésre került, így szeretettel köszöntünk a program referensei között, és kívánjuk Isten áldását a <?=$organization->name;?>
	szervezetben végzett szolgálatodra.</p>

<p>
	Mostantól a megadott email címeddel és jelszavaddal be tudsz jelentkezni a MEET online tervezőfelületére, ahol lehetőséged nyílik megtervezni és nyomon követni az általad
	képviselt gyülekezet teremtésvédelmi programját, munkáját.<br />
	A tervező program működéséről, a modulokról és az önbevallás rendszeréről részletes tájékoztatót találsz honlapunkon, de bármilyen kérdésed esetén keres bennünket bizalommal.
</p>

<p>Ajánljuk figyelmedbe a <a href="<?=Yii::$app->params['email_url'];?>/aktivitas"><?=Yii::$app->params['email_url_display'];?>/aktivitas</a> oldalt, ahol követheted aktuális
	híreinket, a MEET program résztvevőinek híreit, és ahova szeretettel várjuk majd a Te gyülekezetednek híreit, eseményeit és jó gyakorlatait is.</p>

<p>Üdvözlettel,<br />A MEET Csapat nevében:<br />Koltai Zsuzsi – koordinátor</p>