<?php

namespace app\modules\meet\controllers;

use app\modules\meet\models\interfaces\DataTableModelInterface;
use Closure;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class AbstractAdminController
 *
 * @package app\modules\meet\controllers
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
abstract class AbstractAdminController extends Controller {


	/**
	 * @return void
	 */
	public function init() {
		parent::init();
		if (Yii::$app->request->isAjax) {
			$this->layout = 'admin-ajax';
		}
	}


	/**
	 * @param string       $className
	 *
	 * @param Closure|null $qbCallback
	 *
	 * @return Response
	 */
	protected function handlaDTAjax(string $className, Closure $qbCallback = null) {
		$request = Yii::$app->request;

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

		$count = $qb->count();

		if (!is_null(($offset = $request->getQueryParam('start')))) {
			$qb->offset($offset);
		}

		if (!is_null(($limit = $request->getQueryParam('length')))) {
			$qb->limit($limit);
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
			'recordsTotal'    => $count,
			'recordsFiltered' => $count,
			'data'            => $results
		]);
	}


}