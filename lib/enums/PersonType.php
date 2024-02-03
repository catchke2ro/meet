<?php

namespace app\lib\enums;

/**
 * Class PersonType
 *
 * @author Adam Balint <catchke2ro@miheztarto.hu>
 */
enum PersonType: string {

	case MeetReferee = 'meet_referee';
	case Superintendent = 'superintendent';
	case Pastor = 'pastor';
	case PastorGeneral = 'pastor_general';


	/**
	 * @return string
	 */
	public function getLabel(): string {
		return match ($this) {
			self::MeetReferee => 'MEET Referens',
			self::Superintendent => 'Felügyelő',
			self::Pastor => 'Lelkész',
			self::PastorGeneral => 'Főlelkész',
		};
	}


}
