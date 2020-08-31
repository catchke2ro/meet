<?php
/**
 * @var Person $person
 * @var Organization $organization
 */

use app\models\lutheran\Organization;
use app\models\lutheran\Person;

?>
<p>Új regisztráció</p>

<p>Adatok:</p>

<ul>
	<li>Név: <?=$person->nev_elotag ? $person->nev_elotag.' ' : '';?><?=$person->nev;?></li>
	<li>Név: <?=$person->emailContact ? $person->emailContact->ertek1 : '';?></li>
	<li>Szervezet: #<?=$organization->id;?> <?=$organization->nev;?></li>
</ul>