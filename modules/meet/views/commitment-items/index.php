<?php
/* @var $this \yii\web\View */
/* @var $category CommitmentCategory */

use app\modules\meet\models\CommitmentCategory;

$this->title = 'Vállalások';

?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h6>Kategória: <?=$category->name;?> [<a href="/meet/commitment-categories">Vissza a kategóriákhoz</a>]</h6>
				<a href="/meet/commitment-items/create?categoryId=<?=$category->id;?>" class="btn btn-primary pull-right">Új Vállalás</a>
			</div>
			<div class="card-body">
				<table class="dataTable table table-bordered table-hover" data-ajax="/meet/commitment-items?categoryId=<?=$category->id;?>" data-order='[[2,"asc"]]'>
					<thead>
					<tr>
						<th data-data="id" data-orderable="1">ID</th>
						<th data-data="name" data-orderable="1">Név</th>
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



