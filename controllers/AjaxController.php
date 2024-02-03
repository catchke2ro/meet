<?php

namespace app\controllers;

use app\models\Module;
use app\models\Organization;
use app\models\OrgCommitmentFill;
use Yii;
use yii\web\Controller;
use yii\web\Response;

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
	 * @return Response
	 */
	public function actionOrgList($term = null): Response {
		$organizations = [];
		if ($term && strlen($term) >= 2) {
			$organizations = Organization::getList($term, Yii::$app->params['registration_org_types'], false);
			$organizations = array_map(function (Organization $organization) {
				return [
					'id'   => $organization->id,
					'text' => $organization->name
				];
			}, $organizations);
		}

		array_unshift($organizations, ['id' => '', 'text' => ' - ']);

		return $this->asJson([
			'results' => $organizations
		]);
	}


	/**
	 * @return Response
	 */
	public function actionOrganizations(): Response {
		$qb = Organization::find();
		$qb->from(Organization::tableName() . ' AS organization');
		$qb->select([
			'organization.id',
			'organization.organization_type_id',
			'organization.name'
		]);
		$qb->innerJoinWith('organizationType as organizationType');
		$qb->joinWith('commitmentFills as commitmentFills');
		$qb->joinWith('addresses as addresses');
		$qb->andWhere(['organization.is_active' => 1]);
		$organizations = $qb->all();

		$organizations = array_map(function (Organization $organization) {
			$lastModule = null;
			/** @var OrgCommitmentFill[] $fills */
			if (($fills = $organization->commitmentFills)) {
				$fills = array_filter($fills, fn(OrgCommitmentFill $orgCommitmentFill) => $orgCommitmentFill->isApproved);
				usort($fills, function (OrgCommitmentFill $a, OrgCommitmentFill $b) {
					return strtotime($a->date) <= strtotime($b->date) ? - 1 : 1;
				});
				$lastFill = end($fills);
				$lastModule = $lastFill->getFinalModule();
			}

			return [
				'orgId'          => $organization->id,
				'orgTypeId'      => $organization->organizationTypeId,
				'orgTypeName'    => $organization->organizationType->name,
				'name'           => $organization->name,
				'address'        => $organization->address?->getAddressString(),
				'coordinates'    => $organization->address?->getLatLng(),
				'lastModuleId'   => $lastModule?->id,
				'lastModuleName' => $lastModule?->name,
				'markerIcon'     => '/assets/img/map_markers/terkepikon_' . $this->getMarker($organization, $lastModule) . '.png'
			];
		}, $organizations);

		return $this->asJson($organizations);
	}


	/**
	 * @param Organization $organization
	 * @param Module|null  $module
	 *
	 * @return string
	 */
	protected function getMarker(Organization $organization, ?Module $module): string {
		$moduleSlug = $module ? $module->slug : Module::firstModule()->slug;
		$typeSlug = $organization->organizationType->slug;

		return $moduleSlug . '_' . $typeSlug;
	}


}
