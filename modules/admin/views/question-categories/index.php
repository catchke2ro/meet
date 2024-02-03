<?php
/* @var $this \yii\web\View */

$this->title = 'Kérdés kategóriák';

?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<a href="/admin/question-categories/create" class="btn btn-primary pull-right">Új kategória</a>
			</div>
			<div class="card-body">
				<table class="dataTable table table-bordered table-hover" data-ajax="/admin/question-categories" data-order='[[2,"asc"]]'>
					<thead>
					<tr>
						<th data-data="id">ID</th>
						<th data-data="name">Név</th>
						<th data-data="order" data-orderable="1">Sorrend</th>
						<th data-data="orgTypes">Típusok</th>
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




