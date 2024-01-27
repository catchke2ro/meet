<?php
/**
 * @var CommitmentCategory $category
 */

use app\modules\meet\models\CommitmentCategory;

?>

<?php foreach ($category->items as $item) { ?>
	<?php $cookieKey = 'cei_' . $item->id; ?>
	<tr data-widget="expandable-table" aria-expanded="<?=in_array($cookieKey, $state) ? 'true' : 'false';?>" data-id="<?=$cookieKey;?>">
		<?=$this->render('item', ['item' => $item]);?>
	</tr>
	<tr class="expandable-body">
		<td>
			<div class="">
				<table class="table table-hover">
					<tbody class="options">
					<?=$this->render('options', ['item' => $item, 'state' => $state]);?>
					</tbody>
				</table>
			</div>
		</td>
	</tr>
<?php } ?>

