<?php

namespace app\modules\meet;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Class Module
 *
 * @package app\modules\meet
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Module extends \yii\base\Module implements BootstrapInterface {

	public $controllerNamespace = 'app\modules\meet\controllers';

	public $name = 'MEET';

	public $defaultRoute = 'index';

	/**
	 * @var mixed|object|null
	 */
	private $urlPrefix = 'meet';

	/**
	 * @var mixed|object|null
	 */
	private $urlRules = [
		'upload' => 'meet/upload/index',

		'question-categories'                 => 'question-categories/index',
		'question-categories/create'          => 'question-categories/create',
		'question-categories/edit/<id:\d+>'   => 'question-categories/edit',
		'question-categories/delete/<id:\d+>' => 'question-categories/delete',

		'question-items/<categoryId:\d+>'        => 'question-items/index',
		'question-items/create/<categoryId:\d+>' => 'question-items/create',
		'question-items/edit/<id:\d+>'           => 'question-items/edit',
		'question-items/delete/<id:\d+>'         => 'question-items/delete',

		'question-options/<itemId:\d+>'        => 'question-options/index',
		'question-options/create/<itemId:\d+>' => 'question-options/create',
		'question-options/edit/<id:\d+>'       => 'question-options/edit',
		'question-options/delete/<id:\d+>'     => 'question-options/delete',

		'commitment-categories'                              => 'commitment-categories/index',
		'commitment-categories/create'                       => 'commitment-categories/create',
		'commitment-categories/edit/<id:\d+>'                => 'commitment-categories/edit',
		'commitment-categories/delete/<id:\d+>'              => 'commitment-categories/delete',
		'commitment-categories/reorder/<id:\d+>/<direction>' => 'commitment-categories/reorder',

		'commitment-items/<categoryId:\d+>'             => 'commitment-items/index',
		'commitment-items/create/<categoryId:\d+>'      => 'commitment-items/create',
		'commitment-items/edit/<id:\d+>'                => 'commitment-items/edit',
		'commitment-items/delete/<id:\d+>'              => 'commitment-items/delete',
		'commitment-items/reorder/<id:\d+>/<direction>' => 'commitment-items/reorder',

		'commitment-options/<itemId:\d+>'                 => 'commitment-options/index',
		'commitment-options/create/<itemId:\d+>'          => 'commitment-options/create',
		'commitment-options/edit/<id:\d+>'                => 'commitment-options/edit',
		'commitment-options/delete/<id:\d+>'              => 'commitment-options/delete',
		'commitment-options/reorder/<id:\d+>/<direction>' => 'commitment-options/reorder',

		'modules'                 => 'modules/index',
		'modules/create'          => 'modules/create',
		'modules/edit/<id:\d+>'   => 'modules/edit',
		'modules/delete/<id:\d+>' => 'modules/delete',

		'posts'                 => 'posts/index',
		'posts/create'          => 'posts/create',
		'posts/edit/<id:\d+>'   => 'posts/edit',
		'posts/delete/<id:\d+>' => 'posts/delete',

		'users'                 => 'users/index',
		'users/create'          => 'users/create',
		'users/edit/<id:\d+>'   => 'users/edit',
		'users/delete/<id:\d+>' => 'users/delete',

		'org-commitments'          => 'org-commitments/index',
		'org-commitments/<id:\d+>' => 'org-commitments/view',

		'trees/<type>' => 'trees/index',
	];


	/**
	 * @param $app
	 *
	 * @return void
	 * @throws \yii\base\InvalidConfigException
	 */
	public function bootstrap($app) {
		Yii::setAlias('meetbase', __DIR__ . '/meetbase');

		$configUrlRule = [
			'prefix' => $this->urlPrefix,
			'rules'  => $this->urlRules,
			'class'  => 'yii\web\GroupUrlRule'
		];
		$rule = Yii::createObject($configUrlRule);

		Yii::$app->urlManager->addRules([$rule], false);
	}


	/**
	 * {@inheritdoc}
	 */
	public function init() {
		parent::init();
	}


}
