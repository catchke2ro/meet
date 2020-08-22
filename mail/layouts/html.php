<?php

use yii\helpers\Html;
use yii\mail\MessageInterface;
use yii\web\View;

/* @var $this View view component instance */
/* @var $message MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<span class="preheader"></span>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
	<tr>
		<td>&nbsp;</td>
		<td class="container">
			<div class="content">
				<!-- START CENTERED WHITE CONTAINER -->
				<table role="presentation" class="main">
					<!-- START MAIN CONTENT AREA -->
					<tr>
						<td class="wrapper">
							<table role="presentation" border="0" cellpadding="0" cellspacing="0">
								<tr class="header">
									<td class="logo">
										<img src="<?=Yii::$app->params['email_url'];?>/assets/img/meet_logo_webre_fektetett.png"
											 alt="MEET logo" />
									</td>
								</tr>
								<tr class="header">
									<td class="subject"><h1><?=$this->params['__subject'];?></h1></td>
								</tr>
								<tr>
									<td><?=$content?></td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- END MAIN CONTENT AREA -->
				</table>
				<!-- END CENTERED WHITE CONTAINER -->
				<!-- START FOOTER -->
				<div class="footer">
					<table role="presentation" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td class="content-block">
								<span>meet.lutheran.hu</span>
							</td>
						</tr>
					</table>
				</div>
				<!-- END FOOTER -->
			</div>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>
</body>
</html>

