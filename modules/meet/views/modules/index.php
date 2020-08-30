<?php
/* @var $this \yii\web\View */

$this->title = 'Modulok';

?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<a href="/meet/modules/create" class="btn btn-primary pull-right">Új modul</a>
			</div>
			<div class="card-body">
				<table class="dataTable table table-bordered table-hover" data-ajax="/meet/modules" data-order='[[2,"asc"]]'>
					<thead>
					<tr>
						<th data-data="id" data-orderable="1">ID</th>
						<th data-data="name" data-orderable="1">Név</th>
						<th data-data="threshold" data-orderable="1">Határpontszám</th>
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




