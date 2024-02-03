<?php
/**
 * @var $this     \yii\web\View
 * @var $category CommitmentCategory
 * @var $item     CommitmentItem
 * @var $option   CommitmentOption
 * @var $state    array
 */

use app\models\CommitmentCategory;
use app\models\CommitmentItem;
use app\models\CommitmentOption;

$this->title = 'Bejegyzések';
?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
			</div>
			<div class="card-body">
				<table class="table table-hover tree" data-cookie-name="commitment-tree-state">
					<tbody class="categories">
					<?=$this->render('parts/categories', ['categories' => $categories, 'state' => $state]);?>
					<tr>
						<td>
							<a href="/admin/commitment-categories/create" class="addLink btn btn-primary pull-right" title="<?='Kategória hozzáadása';?>" target="_blank">Új kategória</a>
						</td>
					</tr>
					</tbody>
				</table>


				<div class="modal treeModal" tabindex="-1" role="dialog">
					<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"></h5>
								<div class="spinner-border d-none" role="status">
									<span class="sr-only">Loading...</span>
								</div>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
								<button type="button" class="btn btn-primary submit">Mentés</button>
								<button type="button" class="btn btn-primary submit withClose">Mentés és bezárás</button>
							</div>
						</div>
					</div>
				</div>
			</div>



		</div>
	</div>
</div>




