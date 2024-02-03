<?php


namespace app\components;

use app\models\User;
use yii\rbac\PhpManager;

/**
 * Class User
 *
 * @package app\lib
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class AuthManager extends PhpManager {


	/**
	 * @param int|string $userId
	 * @param string     $permissionName
	 * @param array      $params
	 *
	 * @return bool|void
	 */
	public function checkAccess($userId, $permissionName, $params = []) {
		if ($permissionName === 'admin') {
			$user = User::findOne(['id' => $userId]);

			return $user && $user->isAdmin();
		}

		return true;
	}


}
