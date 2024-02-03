<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\assets;

use Yii;
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
		'/dist/app.css',
		'https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap'
	];

	/**
	 * @var array
	 */
	public $js = [
		'/dist/app.js',
		'https://www.google.com/recaptcha/api.js?render={siteKey}'
	];


	public function init() {
		parent::init();

		$baseDir = Yii::$app->getBasePath().'/web';

		if (str_starts_with(Yii::$app->requestedRoute, 'admin/')) {
			$this->js = [];
			$this->css = [];
		}

		foreach ($this->js as &$js) {
			$js = preg_replace('/\{siteKey\}/', Yii::$app->params['recaptcha_site_key'], $js);
			if (str_starts_with($js, '/') && file_exists($baseDir.$js)) {
				$js.='?'.filemtime($baseDir.$js);
			}
		}

		foreach ($this->css as &$css) {
			if (str_starts_with($css, '/') && file_exists($baseDir.$css)) {
				$css.='?'.filemtime($baseDir.$css);
			}
		}
	}

}
