<?php
/**
 * @var CommitmentCategory $category
 */

use app\modules\meet\models\CommitmentCategory;

$hasOptions = count($category->items) > 0;
?>
<td class="treeRow" id="category-<?=$category->id;?>">
	<button type="button" class="btn btn-primary p-0">
		<i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
	</button>
	<div>
		<p class="order">#<?=$category->order;?></p>
		<p class="name"><?=$category->name;?></p>
		<div class="actions">
			<a class="moveUpLink fa fa-arrow-up"
			   href="/meet/commitment-categories/reorder/<?=$category->id;?>/-1"
			   title="Mozgatás fel"
			   target="_blank"></a>
			<a class="moveDownLink fa fa-arrow-down"
			   href="/meet/commitment-categories/reorder/<?=$category->id;?>/1"
			   title="Mozgatás le"
			   target="_blank"></a>
			<a class="addLink fa fa-plus"
			   href="/meet/commitment-items/create/<?=$category->id;?>"
			   title="<?='Elem hozzáadása';?>"
			   target="_blank"></a>
			<a class="deleteLink fa fa-trash <?=($hasOptions ? 'disabled' : '');?>"
			   href="/meet/commitment-categories/delete/<?=$category->id;?>"
			   title="<?=$hasOptions ? 'Nem törölhető, míg vannak elemei' : sprintf('Kategória törlése: %s', $category->name);?>"
			   target="_blank"></a>
			<a class="editLink fa fa-pencil"
			   href="/meet/commitment-categories/edit/<?=$category->id;?>"
			   title="<?=sprintf('Kategória szerkesztése: %s', $category->name);?>"
			   target="_blank"></a>
		</div>
		<p class="description"><?=$category->description;?></p>
	</div>
</td>