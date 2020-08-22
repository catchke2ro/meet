<?php


namespace app\models\lutheran;

use meetbase\models\lutheran\User as BaseUser;

/**
 * Class User
 *
 * @package app\models\lutheran
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class User extends BaseUser {


	/**
	 * @param $username
	 *
	 * @return User|null
	 */
	public static function findByUsername($username): ?User {
		return parent::findOne(['id' => $username]);
	}


}
