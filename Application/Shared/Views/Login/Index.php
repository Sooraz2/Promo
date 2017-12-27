<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php use Infrastructure\CookieVariable;

        echo  $Language->translate("Unifun Promo")?> : Login</title>
    <!-- <link href="includes/styles/style.css" rel="stylesheet" type="text/css"/> -->
    <link rel="shortcut icon" type="image/png" href="<?php echo  BASE_URL ?>favicon.ico"/>
    <link rel="stylesheet" href="<?php echo  BASE_URL ?>includes/styles/style.default.css" type="text/css"/>
    <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/jquery-migrate-1.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo  BASE_URL ?>includes/scripts/Shared.js"></script>



    <?php  if(isset($LoginLog->login_failed) && $DisableLogin!=null): ?>
    <script>

        var currentDateTimeString ="<?php echo  $DateTimeNow ?>";
        var failedDateTimeString = "<?php echo  $LoginLog->last_failed_login ?>";
        var currentDateTime = new Date(currentDateTimeString.split(" ")[0]+"T"+currentDateTimeString.split(" ")[1]);
        var failedDateTime = new Date(failedDateTimeString.split(" ")[0]+"T"+failedDateTimeString.split(" ")[1]);
        var timeDiff = Math.abs(currentDateTime.getTime() - failedDateTime.getTime())/1000;
        countDown = 20*60 - timeDiff;
        var minutes = parseInt(countDown/60);
        var seconds = countDown % 60;
        var triggerInterval;

        $(function(){
            $(".loginwrapper").prepend('<div id="TimedOut"><p> <?php echo  $Language->translate("You have been disabled") ?> <span id="MinutesLeft"></span><span id="TimeDivider">:</span><span id="SecondsLeft"></span></p></div>');
            showTime(minutes, seconds);
            triggerInterval = self.setInterval(countDownTime,1000);
        });

        function showTime(minutes,seconds ){
            minutes = ""+minutes;
            seconds = ""+seconds;
            if(minutes.length<2){
                minutes = "0"+minutes;
            }
            if(seconds.length < 2){
                seconds = "0"+seconds;
            }
            $("#MinutesLeft").html(minutes);
            $("#SecondsLeft").html(seconds);
        }

        function countDownTime(){
            var seconds = parseInt($("#SecondsLeft").html());
            var minutes = parseInt($("#MinutesLeft").html());
            if((minutes <=0 && seconds <= 1) || minutes < 0){
                clearInterval(triggerInterval);
                $("#TimedOut").remove();
            }

            if(seconds == 0){
                minutes = minutes - 1;
                seconds = 59;
            }
	        seconds = seconds - 1;
            showTime(minutes, seconds);
        }
    </script>
   <?php endif;?>
</head>

<body class="loginbody">
<div class="loginwrapper">

    <div class="loginwrap zindex100 animate2 bounceInDown">
        <h1 class="logintitle"><span class="iconfa-lock"></span> <?php echo  $Language->translate("Sign In") ?> <span class="subtitle">Unifun Promo</span>
        </h1>
        <div class="loginwrapperinner">
            <form action="" method="post" id="FormChangeLanguage" name="FormChangeLanguage" class="pull-right">
                <span style="color: #FFF;font-weight: bold;"><?php echo  $Language->translate("Language") ?>:</span>
                <select id="inputLanguage" name="inputLanguage"
                        onchange=" return changeLanguage($(this).val(), '<?php echo  BASE_URL ?>');" class="animate4 bounceIn "
                        style="width: 60px">

                    <?php if ($Config->InterfaceLanguage->Russian == 1): ?>

                        <option value="Russian"
                                <?php if (isset($_COOKIE[CookieVariable::$BalancePlusLanguage])&&$_COOKIE[CookieVariable::$BalancePlusLanguage] == "Russian"): ?>selected <?php endif ?>>
                            Ru
                        </option>

                    <?php endif ?>

                    <?php if ($Config->InterfaceLanguage->English == 1): ?>

                        <option value="English"
                                <?php if (isset($_COOKIE[CookieVariable::$BalancePlusLanguage])&&$_COOKIE[CookieVariable::$BalancePlusLanguage] == "English"): ?>selected <?php endif ?>>
                            En
                        </option>

                    <?php endif ?>
                    <?php if ($Config->InterfaceLanguage->French == 1): ?>

                        <option value="French"
                            <?php if (isset($_COOKIE[CookieVariable::$BalancePlusLanguage])&&$_COOKIE[CookieVariable::$BalancePlusLanguage] == "French"): ?> selected <?php endif ?>>
                            Fr
                        </option>

                    <?php endif ?>


                   
                </select>
                <input type="hidden" name="redirectUrl" value="">
            </form>

            <div class="clearfix clearSpace"></div>

            <form id="login" action="" method="POST" autocomplete="off">
                <?php if ($errorMessage != null&&$errorMessage!=""): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><?php echo $Language->translate("Login error!")?></strong> <?php echo  $errorMessage ?>.
                </div>
               <?php endif?>
                <p class="animate4 bounceIn">
                    <input type="text" name="Username" id="Username" placeholder="Username" value="" autofocus="">
                </p>


                <p class="animate5 bounceIn">
                    <input type="password" name="Password" id="Password" placeholder="Password" value="">
                </p>

                <input type="hidden" name="LoginSubmit" value="Login">

                <p class="animate6 bounceIn">
                    <button class="btn btn-default btn-block"><?php echo  $Language->translate("Login") ?></button>
                </p>



            </form>

        </div>
        <!--loginwrapperinner-->
    </div>
    <div class="loginshadow animate3 fadeInUp"></div>
</div>


<script type="text/javascript">
    //jQuery.noConflict();

    jQuery(document).ready(function () {

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
