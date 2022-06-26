<?php
/* @var $this \yii\web\View */

$this->title = 'Kitöltött vállalások';

?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
			</div>
			<div class="card-body">
				<table class="dataTable table table-bordered table-hover" data-ajax="/meet/org-commitments" data-order='[[1,"desc"]]'>
					<thead>
					<tr>
						<th data-data="id">ID</th>
						<th data-data="date">Dátum</th>
						<th data-data="user" data-orderable="1">Szervezeti egység</th>
						<th data-data="targetModule">Cél modul</th>
						<th data-data="score">Pontszám</th>
						<th data-data="actions" data-sortable="false">Műveletek</th>
					</tr>
					</thead>

					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?=$this->render('/partials/dtscript', []);?>



