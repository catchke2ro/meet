<?php

namespace app\models;

use app\models\traits\WithItemTrait;
use yii\db\ActiveRecord;
use yii\web\Request;

/**
 * Class CommitmentOption
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 * @property int            $id
 * @property string         $name
 * @property int            $order
 * @property bool           $is_custom_input
 * @property string         $description
 * @property CommitmentItem $item
 */
class CommitmentOption extends ActiveRecord {

	use WithItemTrait;


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return '{{commitment_options}}';
	}


	/**
	 * @param Request $request
	 *
	 * @param int     $instance
	 *
	 * @return bool
	 */
	public function isChecked(Request $request, int $instance): bool {
		$checked = false;
		if ($this->item && $this->item->isOnlyCustomInput()) {
			$checked = true;
		}
		if ($request->isPost &&
			$this->item &&
			!empty($request->getBodyParam('options')) &&
			!empty($request->getBodyParam('options')[$this->item->id][$this->id][$instance])
		) {
			$checked = true;
		}

		return $checked;
	}


}
