<!DOCTYPE html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php use Infrastructure\CookieVariable;

        echo  $Language->translate("Balance Plus") ?>: Login</title>

    <link rel="stylesheet" href="<?php echo  BASE_URL ?>includes/styles/style.default.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo  BASE_URL ?>includes/styles/validationEngine.jquery.css" type="text/css"/>
    <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/jquery.validationEngine.js"></script>

    <?php if (isset($_COOKIE[CookieVariable::$BalancePlusLanguage]) && $_COOKIE[CookieVariable::$BalancePlusLanguage] == "Russian"): ?>
        <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/jquery.validationEngine-fr.js"></script>
    <?php else: ?>
        <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/jquery.validationEngine-en.js"></script>
    <?php endif; ?>
    <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/Shared.js"></script>
    <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/jquery-migrate-1.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/Shared.js"></script>

</head>

<body class="loginbody">
<div class="loginwrapper">

    <?php if (isset($_SESSION["ConfirmationMessage"]) && $_SESSION["ConfirmationMessage"] != "" && $_SESSION["ConfirmationMessage"] != null): ?>
        <div id="MessageBox">
            <?php include_once SHARED_VIEW . "ConfirmationMessage.php" ?>

        </div>
    <?php endif ?>

    <div class="loginwrap zindex100 animate2 bounceInDown">
        <h1 class="logintitle"><span class="iconfa-lock"></span> <?php echo  $Language->translate("Change Password") ?> <span
                class="subtitle">Unifun : Balance Plus</span>
        </h1>

        <div class="loginwrapperinner">

            <form action="<?php echo  BASE_URL ?>ChangePassword/ChangedExpiredPassword" method="post" id="FormChangeLanguage"
                  name="FormChangeLanguage" class="pull-right">
                <span style="color: #FFF;font-weight: bold;"><?php echo  $Language->translate("Language") ?>:</span>
                <select id="inputLanguage" name="inputLanguage"
                        onchange=" return changeLanguage($(this).val(), '<?php echo  BASE_URL ?>');" class="animate4 bounceIn "
                        style="width: 60px">
                    <option value="English"
                            <?php if (isset($_COOKIE[CookieVariable::$BalancePlusLanguage])&&$_COOKIE[CookieVariable::$BalancePlusLanguage] == "English"): ?>selected <?php endif ?>>
                        En
                    </option>
                </select>
                <input type="hidden" name="redirectUrl" value="">
            </form>

            <div class="clearfix clearSpace"></div>

            <form id="ChangePassword" action="<?php echo  BASE_URL ?>ChangePassword" method="POST">

                <?php if ($errorMessage != null&&$errorMessage!=""): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <strong><?php echo  $Language->translate("Login error!") ?></strong> <?php echo  $errorMessage ?>.
                    </div>
                <?php endif ?>
                <p class="change-password-info">
                    <span> Your Password hasn't been changed for more than 90 days. We strongly recommend you to change your password </span>
                </p>

                <p class="animate5 bounceIn">
                    <input type="password" name="OldPassword" id="Password" placeholder="Old Password">
                </p>

                <p class="animate5 bounceIn">
                    <input type="password" class="validate[required, custom[password]]" name="NewPassword"
                           id="NewPassword" placeholder="New Password">
                </p>

                <p class="animate5 bounceIn">
                    <input type="password" class="validate[required, equals[NewPassword]]" name="ConfirmPassword"
                           id="ConfirmPassword" placeholder="Confirm Password">
                </p>

                <input type="hidden" name="LoginSubmit" value="ChangePassword">
                <input type="hidden" name="RedirectUrl" value="<?php echo  BASE_URL ?><?php echo  $RedirectURI ?>"/>

                <p class="animate6 bounceIn">
                    <button class="btn btn-default btn-block"><?php echo  $Language->translate("Change Password") ?></button>
                </p>

            </form>
        </div>
    </div>
    <div class="loginshadow animate3 fadeInUp"></div>
</div>


<script type="text/javascript">
    //jQuery.noConflict();

    jQuery(document).ready(function () {

        $('#ChangePassword').validationEngine();

        var anievent = (jQuery.browser.webkit) ? 'webkitAnimationEnd' : 'animationend';
        jQuery('.loginwrap').bind(anievent, function () {
            jQuery(this).removeClass('animate2 bounceInDown');
        });

        jQuery('#Username,#Password').focus(function () {
            if (jQuery(this).hasClass('error')) jQuery(this).removeClass('error');
        });

        jQuery('#loginform button').click(function () {
            if (!jQuery.browser.msie) {
                if (jQuery('#Username').val() == '' || jQuery('#Password').val() == '') {
                    if (jQuery('#Username').val() == '') jQuery('#Username').addClass('error'); else jQuery('#Username').removeClass('error');
                    if (jQuery('#Password').val() == '') jQuery('#Password').addClass('error'); else jQuery('#Password').removeClass('error');
                    jQuery('.loginwrap').addClass('animate0 wobble').bind(anievent, function () {
                        jQuery(this).removeClass('animate0 wobble');
                    });
                } else {
                    jQuery('.loginwrapper').addClass('animate0 fadeOutUp').bind(anievent, function () {
                        jQuery('#loginform').submit();
                    });
                }
                return false;
            }
        });
    });

</script>
</body>
</html>
