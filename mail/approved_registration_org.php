<?php
/**
 * @var Person $person
 * @var Person $pastor
 * @var Person $superintendent
 * @var Person $meetReferer
 * @var Organization $organization
 * @var View $this
 */

use app\models\lutheran\Organization;
use app\models\lutheran\Person;
use yii\base\View;

?>

<p>Kedves <?=$pastor ? $pastor->nev.' ' : '';?>Lelkész és kedves <?=$superintendent ? $superintendent->nev.' ' : '';?>Felügyelő!</p>

<p>Szeretettel köszöntjük a <?=$organization->nev;?> szervezetet a Műveld és őrizd! Evangélikus Egyházi Teremtésvédelmi (MEET) program résztvevői között!</p>

<p>Jelen levelünk tájékoztatás, hogy a gyülekezet presbiteri határozatának végrehajtása megtörtént. Ezennel a <?=$organization->nev;?> gyülekezet a MEET program hivatalos tagja és <?=$meetReferer ? $meetReferer->nev : 'a';?> megbízott a gyülekezet MEET referensi tisztségét hivatalosan is betölti. A megbízott hozzáférést kapott a MEET program adminisztrációs felületéhez, ahol lehetősége lesz összeállítani és a nyomon követni a gyülekezet teremtésvédelmi programját, munkáját. Kérjük, hogy lehetőségeik szerint támogassák a megbízottat szolgálatában.</p>

<p>Szeretettel és tisztelettel ajánljuk figyelmükbe honlapunkat (<a href="<?=Yii::$app->params['email_url'];?>"><?=Yii::$app->params['email_url_display'];?></a>), ahol aktuális híreink mellett a MEET program résztvevőinek híreiről is tájékozódhatnak, illetve ahova szeretettel várjuk majd a <?=$organization->nev;?> gyülekezet híreit, eseményeit és jó gyakorlatait is.</p>

<p>Isten áldása legyen gyülekezetükön és szolgálatukon!</p>

<p>Üdvözlettel,</p>

<p>Koltai Zsuzsi<br />MEET program koordinátor,<br />az Ararát Teremtésvédelmi Munkaág koordinátora</p>
