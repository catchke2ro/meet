<?php
/**
 * @var CommitmentOption $option
 */

use app\models\CommitmentOption;

?>

<td class="treeRow">
	<div>
		<p class="order">#<?=$option->order;?></p>
		<p class="name"><?=$option->name;?></p>
		<p class="points" data-toggle="tooltip" title="Pontszám"><?=$option->score;?></p>
		<div class="actions">
			<a class="moveUpLink fa fa-arrow-up"
			   href="/admin/commitment-options/reorder/<?=$option->id;?>/-1"
			   title="Mozgatás fel"
			   target="_blank"></a>
			<a class="moveDownLink fa fa-arrow-down"
			   href="/admin/commitment-options/reorder/<?=$option->id;?>/1"
			   title="Mozgatás le"
			   target="_blank"></a>
			<a class="deleteLink fa fa-trash"
			   href="/admin/commitment-options/delete/<?=$option->id;?>"
			   title="<?=sprintf('Opció törlése: %s', $option->name);?>"
			   target="_blank"></a>
			<a class="editLink fa fa-pencil"
			   href="/admin/commitment-options/edit/<?=$option->id;?>"
			   title="<?=sprintf('Opció szerkesztése: %s', $option->name);?>"
			   target="_blank"></a>
		</div>
		<p class="description"><?=$option->description;?></p>
	</div>
</td>