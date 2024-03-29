<?php
/**
 * @var CommitmentItem $item
 */

use app\models\CommitmentItem;

$hasOptions = count($item->options) > 0;
?>

<td class="treeRow" id="item-<?=$item->id;?>">
	<button type="button" class="btn btn-primary p-0">
		<i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
	</button>
	<div>
		<p class="order">#<?=$item->order;?></p>
		<p class="active"><?=($item->isActive ? '<i class="fa fa-check"></i>' : '<i class="fa close"></i>');?></p>
		<p class="name"><?=$item->name;?></p>
		<p class="months" data-toggle="tooltip" data-placement="left"
		   title="<?=sprintf(
			   'Vállalható hónapok minimuma: %s<br/>Vállalható hónapok maximuma: %s<br/>Lépésköz: %s',
			   $item->monthsMin ?: '-',
			   $item->monthsMax ?: '-',
			   $item->monthStep ?: '-'
		   );?>">
			<?=sprintf('%s - %s [%s]', $item->monthsMin ?: 'X', $item->monthsMax ?: 'X', $item->monthStep ?: '-');?>
		</p>
		<div class="actions">
			<a class="moveUpLink fa fa-arrow-up"
			   href="/admin/commitment-items/reorder/<?=$item->id;?>/-1"
			   title="Mozgatás fel"
			   target="_blank"></a>
			<a class="moveDownLink fa fa-arrow-down"
			   href="/admin/commitment-items/reorder/<?=$item->id;?>/1"
			   title="Mozgatás le"
			   target="_blank"></a>
			<a class="addLink fa fa-plus"
			   href="/admin/commitment-options/create/<?=$item->id;?>"
			   title="<?='Opció hozzáadása';?>"
			   target="_blank"></a>
			<a class="deleteLink fa fa-trash <?=($hasOptions ? 'disabled' : '');?>"
			   href="/admin/commitment-items/delete/<?=$item->id;?>"
			   title="<?=$hasOptions ? 'Nem törölhető, míg vannak opciói' : sprintf('Elem törlése: %s', $item->name);?>"
			   target="_blank"></a>
			<a class="editLink fa fa-pencil"
			   href="/admin/commitment-items/edit/<?=$item->id;?>"
			   title="<?=sprintf('Elem szerkesztése: %s', $item->name);?>"
			   target="_blank"></a>
		</div>
		<p class="description"><?=$item->description;?></p>
	</div>
</td>