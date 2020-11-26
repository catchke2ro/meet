<?php
/* @var $this \yii\web\View */

$this->title = 'Bejegyzések';

?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<a href="/meet/posts/create" class="btn btn-primary pull-right">Új bejegyzés</a>
			</div>
			<div class="card-body">
				<table class="dataTable table table-bordered table-hover" data-ajax="/meet/posts" data-order='[[2,"asc"]]'>
					<thead>
					<tr>
						<th data-data="id" data-orderable="1">ID</th>
						<th data-data="title" data-orderable="1">Cím</th>
						<th data-data="order" data-orderable="1">Sorrend</th>
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




