<?php


namespace app\components;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Yii;
use yii\base\Component;
use yii\base\View;
use yii\base\ViewEvent;

/**
 * Class Email
 *
 * @package app\components
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Email extends Component {

	/**
	 * @var
	 */
	protected $emailCss = '/web/dist/email.css';


	/**
	 * @param string $template
	 * @param        $to
	 * @param string $subject
	 * @param array  $vars
	 */
	public function sendEmail(string $template, $to, string $subject, array $vars = []) {
		/** @var View $view */
		$mailer = Yii::$app->mailer;
		$view = $mailer->getView();
		$view->on(View::EVENT_AFTER_RENDER, [$this, 'postHtml']);

		$view->params['__subject'] = $subject;
		$mailer->compose($template, $vars)
			->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
			->setTo($to)
			->setSubject($subject)
			->send();
	}


	/**
	 * @param ViewEvent $event
	 */
	public function postHtml(ViewEvent $event) {
		if ($event->output) {
			$cssToInlineStyles = new CssToInlineStyles();
			$event->output = $cssToInlineStyles->convert($event->output, file_get_contents(Yii::$app->getBasePath().$this->emailCss));
		}
	}


}
