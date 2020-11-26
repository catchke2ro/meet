<?php

namespace app\modules\meet;

use Yii;

class Module extends \yii\base\Module {

	public $controllerNamespace = 'app\modules\meet\controllers';

	public $name                = 'MEET';


	public $defaultRoute = 'index';

	/**
	 * @var mixed|object|null
	 */
	private $urlPrefix = 'meet';

	/**
	 * @var mixed|object|null
	 */
	private $urlRules = [
		'/upload' => 'meet/upload/index',

		'/question-categories'                 => 'meet/question-categories/index',
		'/question-categories/create'          => 'meet/question-categories/create',
		'/question-categories/edit/<id:\d+>'   => 'meet/question-categories/edit',
		'/question-categories/delete/<id:\d+>' => 'meet/question-categories/delete',

		'/question-items/<categoryId:\d+>'        => 'meet/question-items/index',
		'/question-items/create/<categoryId:\d+>' => 'meet/question-items/create',
		'/question-items/edit/<id:\d+>'           => 'meet/question-items/edit',
		'/question-items/delete/<id:\d+>'         => 'meet/question-items/delete',

		'/question-options/<itemId:\d+>'        => 'meet/question-options/index',
		'/question-options/create/<itemId:\d+>' => 'meet/question-options/create',
		'/question-options/edit/<id:\d+>'       => 'meet/question-options/edit',
		'/question-options/delete/<id:\d+>'     => 'meet/question-options/delete',

		'commitment-categories'                 => 'meet/commitment-categories/index',
		'commitment-categories/create'          => 'meet/commitment-categories/create',
		'commitment-categories/edit/<id:\d+>'   => 'meet/commitment-categories/edit',
		'commitment-categories/delete/<id:\d+>' => 'meet/commitment-categories/delete',

		'/commitment-items/<categoryId:\d+>'        => 'meet/commitment-items/index',
		'/commitment-items/create/<categoryId:\d+>' => 'meet/commitment-items/create',
		'/commitment-items/edit/<id:\d+>'           => 'meet/commitment-items/edit',
		'/commitment-items/delete/<id:\d+>'         => 'meet/commitment-items/delete',

		'/commitment-options/<itemId:\d+>'        => 'meet/commitment-options/index',
		'/commitment-options/create/<itemId:\d+>' => 'meet/commitment-options/create',
		'/commitment-options/edit/<id:\d+>'       => 'meet/commitment-options/edit',
		'/commitment-options/delete/<id:\d+>'     => 'meet/commitment-options/delete',

		'/modules'                 => 'meet/modules/index',
		'/modules/create'          => 'meet/modules/create',
		'/modules/edit/<id:\d+>'   => 'meet/modules/edit',
		'/modules/delete/<id:\d+>' => 'meet/modules/delete',

		'/posts'                 => 'meet/posts/index',
		'/posts/create'          => 'meet/posts/create',
		'/posts/edit/<id:\d+>'   => 'meet/posts/edit',
		'/posts/delete/<id:\d+>' => 'meet/posts/delete',

		'/user-commitments'          => 'meet/user-commitments/index',
		'/user-commitments/<id:\d+>' => 'meet/user-commitments/view',
	];


	/**
	 * {@inheritdoc}
	 */
	public function init() {
		parent::init();


		Yii::setAlias('meetbase', __DIR__.'/meetbase');

		$configUrlRule = [
			'prefix' => $this->urlPrefix,
			'rules'  => $this->urlRules,
			'class'  => 'yii\web\GroupUrlRule'
		];
		$rule = Yii::createObject($configUrlRule);

		Yii::$app->urlManager->addRules([$rule], false);

		// custom initialization code goes here
	}
}
