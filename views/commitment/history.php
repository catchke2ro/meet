<?php

/**
 * @var array $historyRows
 * @var CommitmentItem $commitment
 */

use app\models\CommitmentItem;

?>

<?php if (!empty($historyRows)) {?>
	<h3><?=$commitment->name;?></h3>
	<table class="table table-sm">
		<thead class="thead-light">
			<tr>
				<th>Dátum</th>
				<th>Opció</th>
				<th>Vállalás időtartama</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($historyRows as $row) { ?>
				<tr>
					<td><?=$row['date'] ?? null;?></td>
					<td><?=$row['name'] ?? null;?></td>
					<td><?=$row['months'] ?? null;?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } ?>
