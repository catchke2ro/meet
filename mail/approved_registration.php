<?php
/**
 * @var Person $person
 * @var View $this
 */

use app\models\lutheran\Person;
use yii\base\View;

?>
<p>Kedves <?=$person->nev;?>!</p>

<p>Regisztrációd elfogadásra került, és be tudsz jelentkezni</p>
<?=$this->render('_button', [
		'link' => Yii::$app->params['email_url'].'/belepes',
		'text' => 'Bejelentkezés'
]);?>
