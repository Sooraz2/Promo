<?php

?>
<form action="<?php echo BASE_URL?>ChangePassword" method="POST" id="ChangePasswordForm" class="form-horizontal">
    <div ID="changePasswordForm">
        <input type="hidden" id="id" value="" name="id"/>

        <div class="form-group">
            <div class="col-xs-6"><label class="control-label" for="OldPassword"><?php echo $Language->translate("Old Password")?></label></div>
            <div class="col-xs-6"><input type="password" class="form-control validate[required]" id="OldPassword"
                                         value=""
                                         name="OldPassword"/></div>
        </div>
        <div class="form-group">
            <div class="col-xs-6"><label class="control-label" for="NewPassword"><?php echo $Language->translate("New Password")?></label></div>
            <div class="col-xs-6"><input type="password" class="form-control validate[required, custom[password]]" id="NewPassword"
                                         name="NewPassword"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-6"><label class="control-label" for="ConfirmPassword"><?php echo $Language->translate("Confirm Password")?></label></div>
            <div class="col-xs-6"><input type="password" class="form-control validate[required,equals[NewPassword]]"
                                         id="ConfirmPassword"
                                         name="ConfirmPassword"/></div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <input type="hidden" name="RedirectUrl" value="<?php echo $_GET["RedirectUrl"]?>">
                <input type="submit" class="btn btn-success" value="<?php echo $Language->translate("Change")?>" name="ChangePasswordButton"/>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(function () {
        $('#ChangePasswordForm').validationEngine();
    });
</script>