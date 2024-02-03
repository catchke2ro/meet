<?php

namespace app\modules\admin\models\forms;

use app\models\User;
use Exception;
use Yii;

/**
 * Class UserEdit
 *
 * UserEdit form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class UserEdit extends UserCreate {

	public ?User $user;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
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
	public function loadUser(User $user): void {
		$this->user = $user;
		$this->username = $user->username;
		$this->email = $user->email;
		$this->isActive = $user->isActive ? 1 : 0;
		$this->isAdmin = $user->isAdmin() ? 1 : 0;
		$this->isApprovedAdmin = $user->isApprovedAdmin ? 1 : 0;
		$this->isApprovedBoss = $user->isApprovedBoss ? 1 : 0;
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
			$user->isActive = $this->isActive ?: false;
			$user->isAdmin = $this->isAdmin ?: false;
			$user->isApprovedBoss = $this->isApprovedBoss ?: false;
			$user->isApprovedAdmin = $this->isApprovedAdmin ?: false;
			$success &= $user->save();
			$transaction->commit();

			return $success ? $user : null;
		} catch (Exception $exception) {
			$transaction->rollBack();
			throw $exception;
		}
	}


}
