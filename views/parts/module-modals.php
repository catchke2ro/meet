<?php
/**
 * @var Module[] $modules
 */

use app\models\Module;

?>

<?php foreach ($modules as $module) { ?>
	<div class="modal moduleModal" id="moduleModal<?=$module->id;?>" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><?=$module->name;?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Bezárás">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<img src="/assets/img/modules/meet_modul_<?=$module->slug;?>_szines_kicsi.png" alt="<?=$module->name;?>" class="selected"/>
					<p><?=$module->description;?></p>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

