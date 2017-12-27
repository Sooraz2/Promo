<?php
/**
 *
 * @var $Language \Language\English\English
 * @var $interactivity_activation \WebInterface\Models\InteractivityActivation
 */


?>

<form action="<?php echo BASE_URL . "BroadcastingCalendar/Save" ?>" id="boardcastschedule-form" method="post"
      class="form-horizontal">

    <input type="hidden" id="ID" name="id" value="<?php echo $All->id; ?>" >
    <input type="hidden" name="country" id="countryID" value="<?php echo $All->country; ?>">
    <input type="hidden" name="operator" id="operatorID" value="<?php echo $All->operator; ?>">
    <input type="hidden" name="selectedDate" value="<?php echo $BroadcastDate; ?>" id="selectedDate">

    <div class="form-group">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Date") ?></label>
        </div>
        <div class="col-sm-8">
            <div id="first_date_picker_container" data-relation="first" class="date-container">

                <input data-placement="top" title="Boardcasting Date Required"
                       data-content="Please Add Boardcasting Date Required" type="text" id="Date-BroadCast"
                       class="form-control validate[required]">
                <?php if($BroadcastDate!=''){ ?>
                <input type="hidden" name="first_date_time[]" class="first_date_picker" value="<?php echo $BroadcastDate ?>">
                <?php } ?>
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
            <select class="form-control validate[required]" name="promotion" id="promotion-dropdown"
                    onchange="ChangeForm(this)">
                <option value="">Select Promotion</option>
                <option data-class="balanceplus">Balance Plus</option>
                <option data-class="ivrbroadcaster">IVR Broadcaster</option>
                <option data-class="icbbroadcaster">ICB Broadcaster</option>
                <option data-class="smsbroadcast">SMS Broadcast</option>
                <option data-class="sns">SNS</option>
                <option data-class="wrongivr">Wrong IVR</option>
                <option data-class="wrongstar">Wrong Star(*)</option>
            </select>
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Service") ?></label>
        </div>
        <div class="col-sm-8">
            <select name="service" class="form-control validate[required,ajax[checkServiceBroadcasting]]">
                <option value="">Select Services</option>
                <?php

                foreach ($OperatorServiceList as $serviceList) {
                    $selected = '';
                    if ($serviceList['Service'] == $All->service) {
                        $selected = 'selected';
                    }
                    echo " <option " . $selected . " value='" . $serviceList['Service'] . "'>" . $serviceList['name'] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group quantity-fields">
        <div class="col-sm-4 ">
            <label
                class="control-label balanceplus"><?php echo $Language->translate("Quantiy of teaser views") ?></label>
            <label class="control-label ivrbroadcaster icbbroadcaster"
                   style="display: none"><?php echo $Language->translate("Quantiy of MSISDNs") ?></label>
            <label class="control-label smsbroadcast"
                   style="display: none"><?php echo $Language->translate("Quantiy of MSISDNs") ?></label>
            <label class="control-label sns"
                   style="display: none"><?php echo $Language->translate("Quantiy of SMS") ?></label>

        </div>
        <div class="col-sm-8">
            <input type="text" name="quantity" value="<?php echo $All->quantity ?>"
                   class="form-control">
        </div>
    </div>

    <div class="form-group ">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Comments") ?></label>
        </div>
        <div class="col-sm-8">
            <div>
                <textarea cols="40" name="comments" rows="3"><?php echo $All->comments ?></textarea>
            </div>

        </div>

    </div>

    <div class="form-group text-field">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Text") ?></label>
        </div>
        <div class="col-sm-8">
            <div style="position: relative">
                <textarea cols="40" name="text[]" rows="3"><?php echo $All->text ?></textarea>
             </div>

            <input type="button" id="AddNewText" class="btn btn-info" value="Add NewText">
        </div>

    </div>

    <div class="form-group pull-right">
        <div class="col-sm-12 dialog-button">
            <input type="submit" name="Submitx" data-val="<?php echo $Save ?>"
                   value="<?php echo $Save ?>"
                   class="btn btn-rounded btn-success">
            <input id="close-form" type="button" value="<?php echo $Language->translate("Cancel"); ?>" onclick="closeDialog(this)"
                   class="btn btn-danger btn-rounded">
        </div>
    </div>
</form>
<script type="text/javascript">
    $(function () {

        $('#boardcastschedule-form').validationEngine();

        var customPicker1 = $('#Date-BroadCast');
        var date = new Date();


        moment.locale('en', {
            week: {dow: 1}
        });
        var myDates = ['<?php echo $BroadcastDate; ?>'];



        var locale = Language.length != 0 && Language == "Russian" ? "en" : "en";
        customPicker1.datetimepickerBootstrapCustom({
            inline: false,
            useCurrent: true,
            showTodayButton: true,
            minDate: false,
            customBalancePlus: true,
            thisDiv: customPicker1,
            globalDateArrayName: myDates,
            targets: [customPicker1],
            defaultDate: moment(date).toDate(),
            selectedDate : $('#selectedDate'),
            locale: locale

        });


        $('#boardcastschedule-form').on('submit', function (e) {

            e.preventDefault();

            if ($("#boardcastschedule-form").validationEngine('validate') == true) {
                if ($('.first_date_picker').length < 1) {

                    $('#Date-BroadCast').popover('show');
                } else {
                    $('#Date-BroadCast').popover('destroy')

                    $.ajax({
                        type: 'post',
                        url: BASE_URL + 'BroadcastingCalendar/Save',
                        data: $(this).serialize(),
                        success: function () {


                            $('#broadcasting-calendar-operator-filter').trigger('change');
                            $('#close-form').trigger('click');

                        }
                    });
                }

            }
        })


        var promotion = '<?php echo $All->promotion; ?>'

        if (promotion != '') {

            $("#promotion-dropdown option:contains(" + promotion + ")").attr('selected', 'selected');

            $('#promotion-dropdown').trigger('change');
        }


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

    $('#AddNewText').on('click',function(){

        var textBox = '<div style="position: relative"><input type="button"  class="btn btn-danger removeText" value="x" style="position: absolute;right: 100%"><textarea class="addedText" cols="40" name="text[]" rows="3"></textarea></div>';

        $(textBox).insertBefore($(this));
    })

    $('body').on('click','.removeText', function(){
        $(this).parent('div').remove();
    })

    $('#promotion-dropdown').on('change',function(){
        $("#boardcastschedule-form").validationEngine('validate');
    })
</script>