<?php

return [
	'senderEmail' => 'meet@lutheran.hu',
	'senderName'  => 'MEET',
	'email_url'   => 'http://meet-teszt.lutheran.hu',

	'email_to_org' => false, //Küldjön-e e-maileket a szervezetnek (vezetők, vagy szervezet e-mail címe)

	'table_prefix' => 'meet__t__',

	'recaptcha_site_key'   => '6LehrcMZAAAAAN1eWRgKB5o7QciHrV5o2IyoM1hn',
	'recaptcha_secret_key' => '6LehrcMZAAAAAI8AeB49YAn0G3klVgGv1AVeWtBi',

	'new_org_kategoria_id'     => 3, //Új szervegység kategória - Külső partner - MEET
	'new_org_default_tipus_id' => 1, //Új szervegység default típus - Egyházközség
	'new_org_erv_allapot'      => 0, //Új szervegység erv_allapot,

	'org_position_valid_erv_allapot' => 1, //Valid erv_allapot login ellenőrzéshez (1036-os eseménynél)
	'org_meet_reg_valid_erv_allapot' => 1, //Valid erv_allapot login ellenőrzéshez (104-es eseménynél)

	'new_person_kategoria_id' => 4, //Új személy kategória - Külső partner - MEET
	'new_person_erv_allapot'  => 1, //Új személy erv_allapot

	'event_type_pozicio'                  => 1036, //Pozíció betöltés esemény típus
	'event_type_meet_reg'                 => 101, //MEET új reg esemény típus
	'event_type_meet_reg_approved'        => 102, //MEET reg approved esemény típus
	'event_type_meet_commitment'          => 103, //MEET új vállalás esemény típus
	'event_type_meet_commitment_approved' => 104, //MEET vállalás elfogadva esemény típus
	'event_type_meet_org_message'         => 105, //MEET üzenet egységnek

	'position_type_megbizott' => 3, //Megbízott pozicio típus
	'position_meet_referer'   => 253, //MEET referens pozíció,
	'position_pastor'         => 1, //Lelkész,
	'position_pastor_general' => 2, //Igazgató lelkész,
	'position_superintendent' => 90, //Felügyelő,

	'registration_org_types' => [1, 1201, 1301, 1401],

	'defult_marker_group' => 'egyeb_kozosseg',
	'marker_groups'       => [
		'diakonia'   => [
			2613 //diakónia
		],
		'gyulekezet' => [
			1, //egyházközség
			1201, //társegyház
			1301, //leányegyház
			1401, //fiókegyház
			1801, //bíróság
		],
		'iroda'      => [
			501, //egyesület/alapítvány
			601, //országos munkaág
			1601, //testület
			1701, //bíróság
			2601, //telephely
			2602, //zsinat
			2603, //presbitérium
			2604, //képviselőtestület
			2605, //közgyűlés
			2606, //bizottság
			2607, //gazdasági
			2620, //munkacsoport
			2622, //számvevőszék
			2624, //országos iroda
			2625, //országos iroda osztálya
			2626, //egyházkormányzati egységek
		],
		'konyvtar'   => [],
		'oktatas'    => [
			2611, //nevelési-oktatási
			2612, //ifjúsági és gyermek
			2627, //tanszék
			2628, //EHE szervezeti egység
		],
		'szallas'    => [
			401, //intézmény
		],
	],
	'token'               => 'yVeU5UcUWgRs9niTrmoZ32UIg1baUsPC',

	'admins' => [
		'abalint',
		'tbagi',
		'zskoltai'
	]
];
