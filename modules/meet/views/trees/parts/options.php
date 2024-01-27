<?php
/**
 * @var CommitmentItem $item
 */

use app\modules\meet\models\CommitmentItem;

?>

<?php foreach ($item->options as $option) { ?>
	<?php $cookieKey = 'ceo_' . $option->id; ?>
	<tr data-id="<?=$cookieKey;?>">
		<?=$this->render('option', ['option' => $option]);?>
	</tr>
<?php } ?>

