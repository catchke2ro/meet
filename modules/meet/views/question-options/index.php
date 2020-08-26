<?php
/* @var $this \yii\web\View */
/* @var $item QuestionItem */

use app\modules\meet\models\QuestionItem;

$this->title = 'Kérdés opciók';

?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h6>Kategória: <?=$item->category->name;?> [<a href="/meet/question-categories">Vissza a kategóriákhoz</a>]</h6>
				<h6>Kérdés: <?=$item->name;?> [<a href="/meet/question-items?categoryId=<?=$item->category->id;?>">Vissza a kérdésekhez</a>]</h6>
				<a href="/meet/question-options/create?itemId=<?=$item->id;?>" class="btn btn-primary pull-right">Új opció</a>
			</div>
			<div class="card-body">
				<table class="dataTable table table-bordered table-hover" data-ajax="/meet/question-options?itemId=<?=$item->id;?>" data-order='[[2,"asc"]]'>
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




