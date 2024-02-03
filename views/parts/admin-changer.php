<?php
/**
 * @var User $user
 */

use app\models\OrganizationType;
use app\models\User;
use app\models\Module;

$selectedOrgType = Yii::$app->session->get('admin_org_type');
$selectedModule = Yii::$app->session->get('admin_active_module');
?>

<?php if ($user && $user->isAdmin()) { ?>
	<div class="card border border-danger">
		<div class="card-body">
			<form action="" method="post" class="form-inline">
				<div class="form-group form-group-sm flex-column mr-3">
					<label class="small">Admin szervezet típus</label>
					<select class="small" name="admin_org_type" class="form-control form-control-sm" onchange="this.form.submit();">
						<?php foreach (OrganizationType::getList() as $orgTypeId => $orgType) { ?>
							<option class="small" value="<?=$orgTypeId;?>" <?=($selectedOrgType == $orgTypeId ? 'selected' : '');?>><?=$orgType;?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group form-group-sm flex-column">
					<label class="small">Admin aktív modul</label>
					<select class="small" name="admin_active_module" class="form-control form-control-sm" onchange="this.form.submit();">
						<option value=""> - Nincs - </option>
						<?php foreach (Module::getList() as $moduleId => $moduleName) { ?>
							<option class="small" value="<?=$moduleId;?>" <?=($selectedModule == $moduleId ? 'selected' : '');?>><?=$moduleName;?></option>
						<?php } ?>
					</select>
				</div>

			</form>
		</div>
	</div>
<?php } ?>

