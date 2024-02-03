<?php

namespace app\models;

use Yii;

/**
 * Class AdminOrganization
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class AdminOrganization extends Organization {


	/**
	 * AdminOrganization constructor.
	 *
	 * @param array $config
	 */
	public function __construct($config = []) {
		parent::__construct($config);

		$orgTypeId = Yii::$app->session->get('admin_org_type') ?: array_key_first(OrganizationType::getModelList());

		$this->id = 0;
		$this->name = 'MEET Teszt Szervezet';
		$this->organizationTypeId = $orgTypeId;
		$this->isActive = true;
	}


	/**
	 * @return Module|null
	 */
	public function getLatestApprovedModule(): ?Module {
		if (($moduleId = Yii::$app->session->get('admin_active_module'))) {
			return Module::findOne(['id' => $moduleId]);
		}

		return parent::getLatestApprovedModule();
	}


}
