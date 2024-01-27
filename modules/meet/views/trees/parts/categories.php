<?php
/**
 * @var CommitmentCategory[] $categories
 * @var array                $state
 */

use app\modules\meet\models\CommitmentCategory;

?>
<?php foreach ($categories as $category) { ?>
	<?php $cookieKey = 'cec_' . $category->id; ?>
	<tr data-widget="expandable-table" aria-expanded="<?=in_array($cookieKey, $state) ? 'true' : 'false';?>" data-id="<?=$cookieKey;?>">
		<?=$this->render('category', ['category' => $category]);?>
	</tr>
	<tr class="expandable-body">
		<td>
			<div class="">
				<table class="table table-hover">
					<tbody class="items">
					<?=$this->render('items', ['category' => $category, 'state' => $state]);?>
					</tbody>
				</table>
			</div>
		</td>
	</tr>
<?php } ?>