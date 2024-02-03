<?php


namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\View;

/**
 * Class Pdf
 *
 * @package app\components
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Pdf extends Component {

	protected string $pdfCss = '/web/dist/pdfCss.css';


	/**
	 * @param string $template
	 * @param string $filename
	 * @param array  $vars
	 *
	 * @return string
	 */
	public function generatePdf(string $template, string $filename, array $vars = []): string {

		$view = new View();
		$html = $view->render($template, $vars);

		$weasy = new \Pontedilana\PhpWeasyPrint\Pdf('/usr/bin/weasyprint');

		$baseDir = Yii::$app->getBasePath() . '/storage/pdf';
		$file = $baseDir . '/' . $filename;

		$weasy->generateFromHtml($html, $file, [], true);

		return $baseDir . '/' . $filename;
	}


}
