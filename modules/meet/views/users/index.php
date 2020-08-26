<?php
/* @var $this \yii\web\View */

$this->title = 'Felhasználók';

?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<a href="/meet/users/create" class="btn btn-primary pull-right">Új felhasználó</a>
			</div>
			<div class="card-body">
				<table class="dataTable table table-bordered table-hover" data-ajax="/meet/users">
					<thead>
					<tr>
						<th data-data="id">ID</th>
						<th data-data="name">Név</th>
						<th data-data="email">E-mail cím</th>
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


