<?php

namespace app\modules\meet\models\forms;

use app\models\User;
use Exception;
use Yii;

/**
 * Class UserEdit
 *
 * UserEdit form
 *
 * @package app\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class UserEdit extends UserCreate {

	/**
	 * @var User|mixed
	 */
	public $user;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		$rules = parent::rules();

		foreach ($rules as &$rule) {
			if (($rule[0] === 'email' && $rule[1] === 'unique') || ($rule[0] === 'username' && $rule[1] === 'unique')) {
				$rule['filter'] = ['!=', 'id', Yii::$app->request->get('id')];
			}
		}

		return array_filter($rules);
	}


	/**
	 * @param User $user
	 */
	public function loadUser(User $user) {
		$organization = $user->organization ?: $user->getAnyActiveOrganization();
		$this->user = $user;
		$this->username = $user->username;
		$this->email = $user->email;
		$this->orgRemoteId = $organization?->id;
		$this->isActive = $user->is_active ? 1 : 0;
		$this->isAdmin = $user->is_admin ? 1 : 0;
		$this->isApprovedAdmin = $user->is_approved_admin ? 1 : 0;
		$this->isApprovedBoss = $user->is_approved_boss ? 1 : 0;
	}


	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function edit(): ?User {
		if (!$this->validate()) {
			return null;
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = true;

			$user = $this->user;
			$user->email = $this->email;
			$user->username = $this->username;
			$user->is_admin = $this->isAdmin ?: false;
			$user->is_approved_boss = $this->isApprovedBoss ?: false;
			$user->is_approved_admin = $this->isApprovedAdmin ?: false;
			$success &= $user->save();
			$transaction->commit();

			return $success ? $user : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}
