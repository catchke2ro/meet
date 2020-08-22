<?php

return [
	'senderEmail' => 'catchke2ro@miheztarto.hu',
	'senderName'  => 'MEET Lutheran',
	'email_url' => 'https://catchke2ro.hu',

	'new_org_kategoria_id' => 3, //Új szervegység kategória - Külső partner - MEET
	'new_org_default_tipus_id' => 1, //Új szervegység default típus - Egyházközség
	'new_org_erv_allapot' => 0, //Új szervegység erv_allapot,

	'org_position_valid_erv_allapot' => 1, //Valid erv_allapot login ellenőrzéshez (1036-os eseménynél)
	'org_meet_reg_valid_erv_allapot' => 1, //Valid erv_allapot login ellenőrzéshez (104-es eseménynél)

	'new_person_kategoria_id' => 4, //Új személy kategória - Külső partner - MEET
	'new_person_erv_allapot' => 1, //Új személy erv_allapot

	'event_type_pozicio' => 1036, //Pozíció betöltés esemény típus
	'event_type_meet_reg' => 101, //MEET új reg esemény típus
	'event_type_meet_reg_approved' => 104, //MEET reg approved esemény típus
	'event_type_meet_commitment' => 102, //MEET új vállalás esemény típus
	'event_type_meet_commitment_approved' => 105, //MEET vállalás elfogadva esemény típus

	'position_type_megbizott' => 3, //Megbízott pozicio típus
	'position_meet_referer' => 253, //MEET referens pozíció
];
