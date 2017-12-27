<?php
/**
 *
 * @var $Language \Language\English\English
 * @var $interactivity_activation \WebInterface\Models\InteractivityActivation
 */

?>

<form action="<?php echo BASE_URL . "BroadcastingCalendar/Save" ?>" id="boardcastschedule-form" method="post"
      class="form-horizontal">

    <input type="hidden" name="id" value="">
    <input type="hidden" name="country" value="<?php echo $Country; ?>">
    <input type="hidden" name="operator" value="<?php echo $Operator; ?>">

    <div class="form-group">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Date") ?></label>
        </div>
        <div class="col-sm-8">
            <div id="first_date_picker_container" data-relation="first" class="date-container">
                <input type="text" id="Date-BroadCast" value="" class="form-control validate[required]">
            </div>

            <div id="second_date_picker_container" data-relation="second" class="date-container"
                 style="margin: 0px auto;max-width: 220px; display: none">
                <div class="col-xs-12 date-range"
                     style="padding:5px 0 5px 0;background: #FFF;margin-bottom:10px;">
                    <div class="col-xs-6 start-date">
                        <label for="startDate"><?php echo $Language->translate("Start Date") ?></label>
                        <input type="text" name="startDate" id="SpecificStartDate"
                               class="form-control input-sm" style="padding:5px"/>
                    </div>
                    <div class="col-xs-6 end-date">
                        <label for="endDate"><?php echo $Language->translate("End Date") ?></label>
                        <input type="text" name="endDate" id="SpecificEndDate"
                               data-prompt-position="topLeft"
                               class="form-control input-sm validate[required, lessThan[SpecificStartDate]]"
                               disabled="disabled"
                               style="padding:5px;"/>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Promotion-Method") ?></label>
        </div>
        <div class="col-sm-8">
            <select class="form-control" name="promotion" onchange="ChangeForm(this)">
                <option value="">Select Promotion</option>
                <option data-class="balanceplus" >Balance Plus</option>
                <option data-class="ivrbroadcaster" >IVR Boardcaster</option>
                <option  data-class="smsbroadcast" >SMS Broadcast</option>
                <option data-class="sns"  >SNS</option>
                <option data-class="wrongivr"  >Wrong IVR</option>
                <option data-class="wrongstar" >Wrong Star(*)</option>
            </select>
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Service") ?></label>
        </div>
        <div class="col-sm-8">
            <select name="service" class="form-control">
                <option value="">Select Services</option>
                <?php
                foreach ($OperatorServiceList as $serviceList) {

                    echo " <option value='" . $serviceList['name'] . "'>" . $serviceList['name'] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group quantity-fields">
        <div class="col-sm-4 ">
            <label
                class="control-label balanceplus"><?php echo $Language->translate("Quantiy of teaser views") ?></label>
            <label class="control-label ivrbroadcaster"
                   style="display: none"><?php echo $Language->translate("Quantiy of MSISDNs") ?></label>
            <label class="control-label smsbroadcast"
                   style="display: none"><?php echo $Language->translate("Quantiy of MSISDNs") ?></label>
            <label class="control-label sns"
                   style="display: none"><?php echo $Language->translate("Quantiy of SMS") ?></label>

        </div>
        <div class="col-sm-8">
            <input type="text" id="UriPattern" name="quantity" value=""
                   class="form-control">
        </div>
    </div>

    <div class="form-group text-field">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Text") ?></label>
        </div>
        <div class="col-sm-8">
            <textarea cols="40" name="text" rows="6"></textarea>
        </div>
    </div>
    <input type="hidden" name="InteractivitySubmit" value="<?php echo "Save" ?>"/>

    <div class="form-group pull-right">
        <div class="col-sm-12 dialog-button">
            <input type="submit" name="Submitx" data-val="<?php echo "Save" ?>"
                   value="<?php echo "Save" ?>"
                   class="btn btn-rounded btn-success">
            <input type="button" value="<?php echo $Language->translate("Cancel"); ?>" onclick="closeDialog(this)"
                   class="btn btn-danger btn-rounded">
        </div>
    </div>
</form>
<script type="text/javascript">
    $(function () {
        $('#InteractivityActivation').validationEngine();

        var customPicker1 = $('#Date-BroadCast');
        var date = new Date();


        moment.locale('en', {
            week: {dow: 1}
        });
        //var myDates = new Array();


        var locale = Language.length != 0 && Language == "Russian" ? "en" : "en";
        customPicker1.datetimepickerBootstrapCustom({
            inline: false,
            useCurrent: true,
            showTodayButton: true,
            minDate: moment(date).toDate(),
            customBalancePlus: true,
            thisDiv: customPicker1,
            globalDateArrayName: "myDates",
            targets: [customPicker1],
            defaultDate: moment(date).toDate(),
            locale: locale

        });

    });

    function ChangeForm(thisObj) {

        var value = $(thisObj).find('option:selected').attr('data-class');

        $('.quantity-fields').show();
        $('.text-field').show();
        $('.quantity-fields').find('label').hide();
        $('.quantity-fields .' + value).show();

        if (value == "wrongivr" || value == "wrongstar") {
            $('.quantity-fields').hide();
            $('.text-field').hide();
        }

    }
</script>