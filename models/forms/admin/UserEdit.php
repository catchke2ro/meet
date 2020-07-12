<?php

namespace app\models\forms\admin;

use app\models\User;
use Exception;
use Yii;

/**
 * Class UserEdit
 *
 * UserEdit form
 *
 * @package app\models\forms\admin
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class UserEdit extends UserCreate {

	/**
	 * @var User|mixed
	 */
	private $user;


	/**
	 * @inheritdoc
	 */
	public function rules() {
		$rules = parent::rules();

		foreach ($rules as &$rule) {
			if ($rule[0] === 'email' && $rule[1] === 'unique') {
				$rule['filter'] = ['!=', 'id', Yii::$app->request->get('id')];
			}
			if ($rule[0] === 'password' && $rule[1] === 'required') {
				$rule = null;
			}
		}
		return array_filter($rules);
	}


	/**
	 * @param User $user
	 */
	public function loadUser(User $user) {
		$this->user = $user;
		$this->orgName = $user->organization->name;
		$this->orgAddress = $user->organization->name;
		$this->orgPhone = $user->organization->phone;
		$this->orgCompanyNumber = $user->organization->company_number;
		$this->orgTaxNumber = $user->organization->tax_number;
		$this->orgRemoteId = $user->organization->remote_id;
		$this->email = $user->email;
		$this->name = $user->name;
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
			$organization = $this->user->organization;
			$organization->email = $this->email;
			$organization->name = $this->orgName;
			$organization->address = $this->orgAddress;
			$organization->phone = $this->orgPhone;
			$organization->company_number = $this->orgCompanyNumber;
			$organization->tax_number = $this->orgTaxNumber;
			$organization->remote_id = (int) $this->orgRemoteId;
			$success &= $organization->save();

			$user = $this->user;
			$user->email = $this->email;
			$user->name = $this->name;
			if ($this->password) {
				$user->setPassword($this->password);
			}
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