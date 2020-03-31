<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class AppAsset extends AssetBundle {

	/**
	 * @var string
	 */
	public $basePath = '@webroot';

	/**
	 * @var string
	 */
	public $baseUrl = '@web';

	/**
	 * @var array
	 */
	public $css = [
		'/dist/main.css',
	];

	/**
	 * @var array
	 */
	public $js = [
		'/dist/scripts.js',
	];

	/**
	 * @var array
	 */
	public $depends = [];
}
