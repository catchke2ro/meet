<?php
/**
 * @var Contact      $contact
 * @var Organization $organization
 */

use app\models\Contact;
use app\models\Organization;

?>
<p>Kedves <?=$organization->name;?>!</p>

<p>A MEET weboldalán keresztül üzenete érkezett a következő adatokkal:</p>
<ul>
	<li>Feladó neve: <?=$contact->name;?></li>
	<li>Feladó e-mail címe: <?=$contact->email;?></li>
	<li>Üzenet: <?=$contact->message;?></li>
</ul>

<p>Üdvözlettel,<br />A MEET Csapat nevében:<br />Koltai Zsuzsi – koordinátor</p>
