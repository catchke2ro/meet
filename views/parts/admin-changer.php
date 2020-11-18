<?php
/**
 * @var User $user
 */

use app\modules\meet\models\OrganizationType;
use meetbase\models\lutheran\User;

$selectedOrgType = Yii::$app->session->get('admin_org_type');
?>

<?php if ($user->isAdmin()) { ?>
	<div class="card border border-danger">
		<div class="card-body">
			<form action="" method="post" class="form-inline">
				<div class="form-group">
					<label class="mr-2">Admin szervezet típus</label>
					<select name="admin_org_type" class="form-control" onchange="this.form.submit();">
						<?php foreach (OrganizationType::getList() as $orgTypeId => $orgType) { ?>
							<option value="<?=$orgTypeId;?>" <?=($selectedOrgType == $orgTypeId ? 'selected' : '');?>><?=$orgType;?></option>
						<?php } ?>
					</select>
				</div>

			</form>
		</div>
	</div>
<?php } ?>

