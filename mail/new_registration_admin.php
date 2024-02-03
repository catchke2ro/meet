<?php
/**
 * @var Person $person
 * @var Organization $organization
 */

use app\models\Organization;
use app\models\Person;

?>
<p>Új regisztráció</p>

<p>Adatok:</p>

<ul>
	<li>Név: <?=$person->name;?></li>
	<li>E-mail: <?=$person->email?->email;?></li>
	<li>Szervezet: #<?=$organization->id;?> <?=$organization->name;?></li>
</ul>