<?php
/* @var $this \yii\web\View */

$this->title = 'Modulok';

?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<a href="/admin/modules/create" class="btn btn-primary pull-right">Új modul</a>
			</div>
			<div class="card-body">
				<table class="dataTable table table-bordered table-hover" data-ajax="/admin/modules" data-order='[[2,"asc"]]'>
					<thead>
					<tr>
						<th data-data="id" data-orderable="1">ID</th>
						<th data-data="name" data-orderable="1">Név</th>
						<th data-data="threshold" data-orderable="1">Határpontszám</th>
					</tr>
					</thead>

					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>



