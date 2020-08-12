<?php

namespace app\controllers\admin;

use app\models\interfaces\DataTableModelInterface;
use app\models\User;
use Closure;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class AbstractAdminController
 *
 * @package app\controllers\admin
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
abstract class AbstractAdminController extends Controller {

	public $layout = 'admin';


	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'allow'         => true,
						'matchCallback' => function ($rule, $action) {
							return Yii::$app->getUser()->getIdentity() && Yii::$app->getUser()->getIdentity()->isAdmin();
						}
					],
				],
			],
			'verbs'  => [
				'class'   => VerbFilter::class,
				'actions' => [],
			],
		];
	}


	/**
	 * @param string $className
	 *
	 * @return Response
	 */
	protected function handlaDTAjax(string $className, Closure $qbCallback = null) {
		$request = Yii::$app->request;

		/** @var User $className */
		$qb = $className::find();

		//Set order-by
		$columns = $request->getQueryParam('columns') ?: [];
		if (($orderBy = $request->getQueryParam('order'))) {
			$orderBy = (array) $orderBy;
			foreach ($orderBy as $item) {
				if (isset($columns[$item['column']]) && in_array($columns[$item['column']]['data'], $className::getOrderableColumns())) {
					$qb->addOrderBy("{$columns[$item['column']]['data']} {$item['dir']}");
				}

			}
		}

		//Set text searches
		if (!empty($searchValue = $request->getQueryParam('search')['value'] ?? '')) {
			$likeConditions = ['or'];
			foreach ($className::getTextSearchColumns() as $searchColumn) {
				$likeConditions[] = ['like', $searchColumn, $searchValue];
			}
			$qb->andWhere($likeConditions);
		}

		if ($qbCallback) {
			$qbCallback($qb);
		}

		$results = $qb->all();

		//Map results to array with actions
		$results = array_map(function (DataTableModelInterface $model) {
			$array = $model->toDataTableArray();
			$array['actions'] = implode('', $model->getDataTableActions());
			return $array;
		}, $results);

		//Datatables response
		return $this->asJson([
			'draw'            => $request->getQueryParam('draw'),
			'recordsTotal'    => count($results),
			'recordsFiltered' => count($results),
			'data'            => $results
		]);
	}


}