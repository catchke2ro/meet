<?php


namespace app\components;

use app\models\lutheran\User;
use Yii;
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
		/** @var \meetbase\models\lutheran\User $user */
		if ($permissionName === 'admin') {
			$user = User::findOne(['vuid' => $userId]);
			return $user && in_array($user->id, Yii::$app->params['admins']);
		}
		return true;
	}



}
