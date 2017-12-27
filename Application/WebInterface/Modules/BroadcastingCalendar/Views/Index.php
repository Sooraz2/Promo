<?php
/**
 *
 * @var $Language \Language\English\English
 */

use Infrastructure\CookieVariable;
use Infrastructure\DefaultLanguages;

$baseUrl = BASE_URL;

$Breadcrumb = "<li><a href='{$baseUrl}ActiveTeasers'>{$Language->translate('Home')}</a></li>
<li class='active'>{$Language->translate('Broadcasting Calendar')}</li>";

$PageTitle = $Language->translate('Broadcasting Calendar');

$PageLeftHeader = "";

include_once SHARED_VIEW . "base.php";

?>

    <div class="content-wrapper">

        <div id="broadcasting-calendar"></div>

    </div>

    <script type="text/javascript">
        var pageIndex = '<?php echo $PageIndex; ?>';
        var activeDate = GetCookie("Balance_Plus_Chinguitel_Broadcasting_calendar_active_date");


        var CountryList = <?php echo json_encode($CountryList); ?>;
        var OperatorList = <?php echo json_encode($OperatorList); ?>;
        var OperatorList = "";
        var OperatorServiceList = <?php echo json_encode($OperatorServiceList); ?>;
        var OperatorServiceList = "";

        $(function () {
            $('.leftmenu').find('.active').removeClass('active');
            $('#menu-BroadCastingCalendar').addClass('active');

            $(".mainwrapper").addClass("no-responsive");


            $('#broadcasting-calendar').broadcastingCalendar({
                url: '<?php echo  BASE_URL ?>BroadcastingCalendar/GetBroadCastingDataNew',
                detailsUrl: '<?php echo  BASE_URL ?>BroadcastingCalendar/GetBroadCastingDetailsDataNew',
                language: '<?php echo  isset($_COOKIE[CookieVariable::$BalancePlusLanguage])?$_COOKIE[CookieVariable::$BalancePlusLanguage]:DefaultLanguages::$DefaultLanguage ?>',
                GuideNotation: '<?php echo  $Language->translate("Guide Notation") ?>',
                BalancePlus: '<?php echo  $Language->translate("Balance+") ?>',
                IVRBoardcaster: '<?php echo  $Language->translate("IVR Broadcaster") ?>',
                ICBBoardcaster: '<?php echo  $Language->translate("ICB Broadcaster") ?>',
                SMSBoardcast: '<?php echo  $Language->translate("SMS Broadcast") ?>',
                SNS: '<?php echo  $Language->translate("SNS") ?>',
                WrongIVR: '<?php echo  $Language->translate("Wrong IVR") ?>',
                WrongStar: '<?php echo  $Language->translate("Wrong Star(*)") ?>',
                Today: '<?php echo  $Language->translate("Today") ?>',
                TeaserID: '<?php echo  $Language->translate("Teaser ID") ?>',
                CallBack : function(){
                    if(activeDate!= null) {
                        $("body").find(".drop-event[date=" + activeDate + "]").trigger("click");
                        //DeleteCookie("Balance_Plus_ZAIN_Broadcasting_calendar_active_date");

                    }
                },
                Data : {
                    CountryList : CountryList,
                    OperatorList:OperatorList,
                    OperatorServiceList : OperatorServiceList
                }
            });

        });

        function IsModifiedDialog(buttonObj) {

            var isModified = $(buttonObj).siblings("[name='is_modified']").val()== "0" ? true : false;
            if (isModified)
                return;
            IsModifiedLatestSummary(buttonObj, "<?php echo $Language->translate('The Teaser was Modified')?>",
                "<?php echo $Language->translate('The teaser was modified. To see the summary data of all teasers versions press: Summary. To see the data of latest teaser press: Latest')?>",
                "<?php echo $Language->translate('Latest Teaser')?>", "<?php echo $Language->translate('Summary')?>");
            return false;
        }
        var OperatorData = '';
        $(function(){


            $("body").on('change','#broadcasting-calendar-country-filter', function(){
                $thisObj = $(this);
            $.ajax({
                url: "<?php echo BASE_URL ?>BroadcastingCalendar/GetOperatorByCountry",
                type: "post",
                data: {"CountryID": $(this).val()},
                success: function (response) {


                    var options = "";

                    var OperatorCheck = new Array();

                    options += '<option value="">Select Operator</option>'

                    if($thisObj.val()>0) {
                        responseJson = JSON.parse(response);
                        responseJson.forEach(function (data) {

                            if (OperatorCheck.indexOf(data["Operator"]) < 0) {

                                OperatorCheck.push(data["Operator"]);
                                options += '<option value="' + data["Operator"] + '">' + data["name"] + '</option>'
                            }
                        });
                    }
                    $("#broadcasting-calendar-operator-filter").html(options);

                }
            });

        })



            $("body").on('change','#broadcasting-calendar-operator-filter', function(){
                $thisObj = $(this);
                $.ajax({
                    url: "<?php echo BASE_URL ?>BroadcastingCalendar/GetServiceByOperator",
                    type: "post",
                    data: {"OperatorID": $(this).val(),"CountryID":$('#broadcasting-calendar-country-filter').val()},
                    success: function (response) {



                            var options = "";
                            options += '<option value="">Select Service</option>'
                        if($thisObj.val()>0) {
                            responseJson = JSON.parse(response);

                            var OperatorCheck = new Array();


                            responseJson.forEach(function (data) {

                                if (OperatorCheck.indexOf(data["Service"]) < 0) {

                                    OperatorCheck.push(data["Service"]);
                                    options += '<option value="' + data["Service"] + '">' + data["name"] + '</option>'

                                }
                            });
                        }
                            $("#broadcasting-calendar-select-service").html(options);


                    }
                });

            })





        })




    </script>
<?php include_once SHARED_VIEW . "footer.php"; ?>