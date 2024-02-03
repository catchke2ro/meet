<?php

namespace app\models;

use app\models\traits\SetGetTrait;
use yii\db\ActiveRecord;

/**
 * Class AbstractModel
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
abstract class BaseModel extends ActiveRecord {


	use SetGetTrait;
}
