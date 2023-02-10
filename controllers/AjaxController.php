<?php

namespace app\controllers;

use app\models\lutheran\Event;
use app\models\lutheran\Organization;
use app\models\Module;
use app\models\OrgCommitmentFill;
use Yii;
use yii\web\Controller;

/**
 * Class AjaxController
 *
 * @package app\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class AjaxController extends Controller {


	/**
	 * Select2 organization search
	 *
	 * @param null $term
	 *
	 * @return string
	 */
	public function actionOrgList($term = null) {
		$orgs = [];
		if ($term && strlen($term) >= 2) {
			$orgs = Organization::getList($term, Yii::$app->params['registration_org_types'], false);
			$orgs = array_map(function (Organization $organization) {
				return [
					'id'   => $organization->id,
					'text' => $organization->nev
				];
			}, $orgs);
		}

		array_unshift($orgs, ['id' => '', 'text' => ' - Nem szereplek az adatbázisban - ']);

		return $this->asJson([
			'results' => $orgs
		]);
	}


	public function actionOrgs() {
		$approvedFillIds = array_map(function (Event $event) {
			return $event->ref1_id;
		}, Event::find()
			->andWhere(['ref_tipus_id' => Yii::$app->params['event_type_meet_commitment_approved']])
			->andWhere(['ertek1' => 1])
			->all()
		);

		$qb = Organization::find();
		$qb->from(Organization::tableName() . ' AS organization');
		$qb->select([
			'organization.id',
			'organization.ref_tipus_id',
			'organization.nev'
		]);
		$qb->innerJoinWith('positionEvent as positionEvent');
		$qb->innerJoinWith('orgType as orgType');
		$qb->joinWith('addressContacts as addressContact');
		$qb->joinWith('gpsContacts as gpsContact');
		$orgs = $qb->all();

		$orgs = array_map(function (Organization $organization) use ($approvedFillIds) {
			$lastModule = null;
			if (($fills = $organization->commitmentFills)) {
				$fills = array_filter($fills, function (OrgCommitmentFill $orgCommitmentFill) use ($approvedFillIds) {
					return in_array($orgCommitmentFill->id, $approvedFillIds);
				});
				usort($fills, function (OrgCommitmentFill $a, OrgCommitmentFill $b) {
					return strtotime($a->date) <= strtotime($b->date) ? - 1 : 1;
				});
				$lastFill = end($fills);
				$lastModule = $lastFill->getFinalModule();
			}

			return [
				'orgId'          => $organization->id,
				'orgTypeId'      => $organization->orgType->id,
				'orgTypeName'    => $organization->orgType->nev,
				'name'           => $organization->nev,
				'address'        => !empty($organization->addressContacts) ? $organization->addressContacts[0]->getAddressString() : null,
				'coordinates'    => !empty($organization->gpsContacts) ? $organization->gpsContacts[0]->getCooridnates() : null,
				'lastModuleId'   => $lastModule?->id,
				'lastModuleName' => $lastModule?->name,
				'markerIcon'     => '/assets/img/map_markers/terkepikon_' . $this->getMarker($organization, $lastModule) . '.png'
			];
		}, $orgs);

		return $this->asJson($orgs);
	}


	/**
	 * @param Organization $organization
	 * @param Module|null  $module
	 *
	 * @return string
	 */
	protected function getMarker(Organization $organization, ?Module $module) {
		$moduleSlug = $module ? $module->slug : Module::firstModule()->slug;
		$typeSlug = Yii::$app->params['defult_marker_group'];
		foreach (Yii::$app->params['marker_groups'] as $slug => $ids) {
			if (in_array($organization->orgType->id, $ids)) {
				$typeSlug = $slug;
				break;
			}
		}

		return $moduleSlug . '_' . $typeSlug;
	}

}
