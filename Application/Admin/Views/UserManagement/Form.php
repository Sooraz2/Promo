<?php
/**
 *
 * @var $loginUser \Admin\Models\LoginUser
 * @var $Language \Language\English\English
 */
?>

<form action="" method="post" class="form-horizontal" id="UserManagementForm">
    <input type="hidden" name="ID" value="<?php echo  $loginUser->ID ?>" id="ID">
    <input type="hidden" name="UserManagementFormToken" value="<?php echo $formToken; ?>" />

    <div class="form-group">
        <div class="col-xs-6">
            <label class="control-label"><?php echo  $Language->Name2 ?></label>
        </div>
        <div class="col-xs-6">
            <input type="text" id="Name" name="Name" value="<?php echo  $loginUser->Name ?>"
                   class="form-control validate[required]">
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-6">
            <label class="control-label"><?php echo  $Language->translate("Email") ?></label>
        </div>
        <div class="col-xs-6">
            <input type="text" id="Email" name="Email" value="<?php echo  $loginUser->Email ?>"
                   class="form-control validate[required,custom[email]]">
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-6">
            <label class="control-label"><?php echo  $Language->translate("Login") ?></label>
        </div>
        <div class="col-xs-6">
            <input type="text" id="Username" name="Username" value="<?php echo  $loginUser->Username ?>"
                   class="form-control validate[required],ajax[ajaxUsernameCallPhp]">
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-6">
            <label class="control-label"><?php echo  $Language->translate("Role") ?></label>
        </div>
        <div class="col-xs-6">
            <select name="UserType" id="UserType" class="form-control validate[required]">
                <option value=""><?php echo  $Language->translate("Select") ?></option>
                <?php if($_SESSION["UserType"]==1): ?>
                    <option value="1" <?php if( $loginUser->UserType==1 ):?>selected<?php endif;?>><?php echo  $Language->translate("admin") ?></option>
                <?php endif?>
                <option value="2" <?php if( $loginUser->UserType==2 ):?>selected<?php endif;?>><?php echo  $Language->translate("Moderator") ?></option>
                <option value="3" <?php if( $loginUser->UserType==3 ):?>selected<?php endif;?>><?php echo  $Language->translate("Operator") ?></option>
                <!--<option value="4" <?php /*if( $loginUser->UserType==4 ):*/?>selected<?php /*endif;*/?>><?php /*echo  $Language->translate("Customer Care") */?></option>-->
            </select>
        </div>

    </div>
    <?php if($loginUser->ID>0):?>
        <div class="form-group">
            <div class="col-xs-6">
                <label class="control-label"><?php echo  $Language->translate("Change Password") ?></label>
            </div>
            <div class="col-xs-6">
                <input type="checkbox" name="ChangePassword" onclick="EnableDisablePasswordChange(this)">
            </div>
        </div>
    <?php endif;?>

    <div class="form-group">
        <div class="col-xs-6">
            <label class="control-label"><?php echo  $Language->translate("Password") ?></label>
        </div>
        <div class="col-xs-6">
            <input type="password"
                   class="form-control validate[required, custom[password]]"   <?php if($loginUser->ID>0):?> disabled <?php endif?>
                   name="Password" id="Password">
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-6">
            <label class="control-label"><?php echo  $Language->translate("Confirm Password") ?> </label>
        </div>
        <div class="col-xs-6">
            <input type="password" class="form-control validate[required,equals[Password]]"
                <?php if($loginUser->ID>0):?> disabled <?php endif?>
                   name="ConfirmPassword" id="ConfirmPassword">
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-6">
            <label class="control-label" style="text-align: left"><?php echo $Language->translate("Send E-mail notifications")?></label>
        </div>
        <div class="col-xs-6">
            <input class="pull-left"  type="checkbox" id="SendNotification" name="SendNotification" value="1" <?php  if($loginUser->SendNotification==1): ?>checked="checked" <?php endif ?>>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-7 col-xs-offset-5 dialog-button" style="text-align: right;">
            <input type="submit" name="UserManagementSubmit" data-val="<?php echo  $saveOrUpdate ?>"
                   value="<?php echo  $Language->translate($saveOrUpdate) ?>"
                   class="btn btn-success btn-rounded">
            <input type="button" value="<?php echo  $Language->translate("Cancel") ?>" onclick="closeDialog(this)"
                   class="btn btn-danger btn-rounded">
        </div>
    </div>
</form>

<script type="text/javascript">
    $(function () {
        $('#UserManagementForm').validationEngine();

        $('input[name="UserManagementSubmit"][data-val="Save"]').click(function () {

            if (!$("#UserManagementForm").validationEngine('validate')) return;

            $(this).closest('.ui-dialog-content').dialog('destroy').remove();
        });
    });
    function EnableDisablePasswordChange($thisObj) {
        if ($($thisObj).is(":checked")) {
            $('#Password').prop('disabled', false);
            $('#ConfirmPassword').prop('disabled', false);
        } else {
            $('#Password').prop('disabled', true);
            $('#ConfirmPassword').prop('disabled', true);
        }
    }

</script>