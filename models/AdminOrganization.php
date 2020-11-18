<?php

namespace app\models;

use app\modules\meet\models\OrganizationType;
use meetbase\models\lutheran\Organization as BaseOrganization;
use Yii;

/**
 * Class AdminOrganization
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class AdminOrganization extends BaseOrganization {


	/**
	 * AdminOrganization constructor.
	 *
	 * @param array $config
	 */
	public function __construct($config = []) {
		parent::__construct($config);

		$orgType = Yii::$app->session->get('admin_org_type') ?: array_key_first(OrganizationType::getList());
		$this->id = 0;
		$this->ref_regi_id = 0;
		$this->ref_kategoria_id = 0;
		$this->ref_tipus_id = $orgType;
		$this->nev = 'MEET Teszt Szervezet';
		$this->erv_allapot = 1;
		$this->kerulet_gen = 0;
	}


}
