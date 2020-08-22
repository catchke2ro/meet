<?php

namespace app\models;

use app\models\interfaces\CategoryInterface;
use meetbase\models\CommitmentCategory as BaseCommitmentCategory;

/**
 * Class CommitmentCategory
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class CommitmentCategory extends BaseCommitmentCategory implements CategoryInterface {

}
