<?php
/**
 * @var User $user
 */

use app\models\User;

?>
<p>Kedves <?=$user->getName();?></p>

<p>Kattints az alábbi linkre, ahol új jelszót adhatsz meg!</p>

<p><a href="<?=Yii::$app->params['email_url'];?>/jelszo-visszaallitas?t=<?=$user->passwordResetToken;?>"><?=Yii::$app->params['email_url'];?>/jelszo-visszaallitas?t=<?=$user->passwordResetToken;?></a></p>

<p>A link 1 óráig érvényes</p>

<p>Üdvözlettel,<br />A MEET Csapat</p>
