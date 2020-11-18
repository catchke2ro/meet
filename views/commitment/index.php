<?php

use app\models\AdminOrganization;
use app\models\CommitmentCategory;
use app\models\Module;
use app\models\OrgCommitmentFill;
use app\models\OrgQuestionFill;
use app\modules\meet\models\OrganizationType;
use meetbase\models\lutheran\User;

/**
 * @var $commitmentCategories                     CommitmentCategory[]
 * @var $fill                                     OrgQuestionFill
 * @var $this                                     yii\web\View
 * @var $modules                                  array|Module[]
 * @var $checkedCommitmentOptions                 array
 * @var $user                                     User
 */

$this->title = 'Vállalások';

?>

<?=$this->render('/parts/admin-changer', [
	'user' => $user,
]);?>

<form method="post" action="" class="commitmentsForm">
	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	<input type="hidden" name="orgType" value="<?=Yii::$app->user->getIdentity()->getOrgTypeId();?>" />




	<?php if (!$fill) { ?>
		<p class="text-center">
			<span>Amennyiben nem vagy biztos a lenti válaszokban, segítségül válaszolj meg pár kérdést:</span><br />
			<a href="/kerdesek" class="btn btn-secondary mt-1">Kérdések</a>
		</p>
	<?php } ?>

	<?php if ($fill instanceof OrgCommitmentFill) { ?>
		<div class="card shadow-none border border-primary">
			<div class="card-body">
				<dl class="row">
					<dt class="col-sm-4">Legutóbbi vállalás időpontja:</dt>
					<dt class="col-sm-8"><?=(new DateTime($fill->date))->format('Y. m. d. H:i:s');?></dt>
					<dt class="col-sm-4">Megszerzett modul:</dt>
					<dt class="col-sm-8"><?=$fill->getFinalModule() ? $fill->getFinalModule()->name : null;?></dt>
				</dl>
			</div>
		</div>
	<?php } ?>

	<?=$this->render('/parts/module-modals', [
		'modules' => $modules,
	]);?>
	<div class="card modules shadow-none">
		<input type="hidden" name="targetModule" id="selectedModule"
			   value="<?=$fill instanceof OrgCommitmentFill && $fill->getFinalModule() ? $fill->getFinalModule()->id : null;?>" />
		<div class="card-body">
			<h3>Válassz modult, kitűzött célt!</h3>
			<ul class="moduleList">
				<?php foreach ($modules as $module) { ?>
					<li>
						<?php if ($module->threshold === 0) { ?>
							<div class="imgWrapper">
								<img src="/assets/img/modules/meet_modul_<?=$module->slug;?>_szines_kicsi.png" alt="<?=$module->name;?>" />
							</div>
							<h5><?=$module->name;?></h5>
						<?php } else { ?>
							<a href="javascript:void(0)" class="selectModule" data-moduleid="<?=$module->id;?>">
								<div class="imgWrapper">
									<img src="/assets/img/modules/meet_modul_<?=$module->slug;?>_feher_kicsi.png" alt="<?=$module->name;?>" class="notSelected" />
									<img src="/assets/img/modules/meet_modul_<?=$module->slug;?>_szines_kicsi.png" alt="<?=$module->name;?>" class="selected" />
								</div>
								<h5><?=$module->name;?></h5>
							</a>
						<?php } ?>
						<a href="javascript:void(0)" class="moduleInfo fa fa-question-circle" data-toggle="modal" data-target="#moduleModal<?=$module->id;?>"></a>
					</li>
				<?php } ?>
			</ul>
			<div class="moduleProgress d-none">
				<div class="progress">
					<div class="progress-bar bg-secondary" role="progressbar"></div>
				</div>
			</div>
		</div>
	</div>

	<!--
	<div class="card">
		<div class="card-header bg-primary">
			Kiértékelés
		</div>
		<div class="card-body">
			<div class="commitmentScore">
				<p>Pontszám: <span class="score"></span></p>
				<p>Összpontszám: <span class="scoreWithSpecial"></span></p>
				<p>Aktuális szint: <span class="currentModule"></span></p>
				<p>Következő szint: <span class="nextModule"></span></p>
				<p>Százalék a következő szintig: <span class="nextModulePercentage"></span>%</p>
				<p>Százalék a cél szintig: <span class="targetModulePercentage"></span>%</p>
			</div>
		</div>
	</div>
	-->

	<div class="accordion treeAccordion mt-5 mb-5" id="commitmentsAccordion">
		<?php
		foreach ($commitmentCategories as $commitmentCategory) {
			echo $this->render('category', [
				'commitmentCategory'       => $commitmentCategory,
				'fill'                     => $fill,
				'checkedCommitmentOptions' => $checkedCommitmentOptions
			]);
		}
		?>
	</div>

	<?php if ($fill && !$fill->isApproved()) { ?>
		<div class="text-center">
			<p>Legutóbbi vállalásod elfogadásra vár. Amíg ez nem történt meg, nem tudsz új vállalást leadni.</p>
		</div>
	<?php } else { ?>
		<?php if (!($user->getOrganization() instanceof AdminOrganization)) {?>
			<div class="text-center">
				<button type="submit" class="btn btn-secondary">Elküldöm a vállalásokat</button>
			</div>
		<?php } ?>
	<?php } ?>
</form>


<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h5 class="modal-title" id="historyModalLabel">Vállalás története:</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-loader d-none">
				<div class="d-flex justify-content-center align-items-center">
					<i class="fa fa-spinner fa-2x fa-spin"></i>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
			</div>
		</div>
	</div>
</div>
