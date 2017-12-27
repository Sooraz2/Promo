<?php
/**
 *
 * @var $Language \Language\English\English
 * @var $interactivity_activation \WebInterface\Models\InteractivityActivation
 */

?>

<form action="<?php echo BASE_URL . "ReportData/Save" ?>" id="reportData-form" method="post"
      class="form-horizontal">

    <input type="hidden" id="ID" name="ID" value="<?php echo $ID ?>">
    <input type="hidden"  name="ReportDataToken" value="<?php echo $formToken ?>">

    <div class="form-group">
        <div class="col-sm-3">
            <label class="control-label"><?php echo $Language->translate("Country") ?></label>
        </div>
        <div class="col-sm-7">
            <input type="text"  name="country" class="form-control validate[required]">

        </div>

    </div>
    <div class="form-group">
        <div class="col-sm-3">
            <label class="control-label"><?php echo $Language->translate("Operator") ?></label>
        </div>
        <div class="col-sm-7">
            <input type="text"  name="operator" class="form-control validate[required]">

        </div>

    </div>
    <div class="form-group">
        <div class="col-sm-3">

            <label class="control-label"><?php echo $Language->translate("Service") ?></label>

        </div>
        <div class="col-sm-7">

            <input type="text"  name="service" class="form-control validate[required]">

        </div>

    </div>

    <div class="form-group">
        <div class="col-sm-3">
            <label class="control-label"><?php echo $Language->translate("Stored Procdure") ?></label>
        </div>
        <div class="col-sm-7">
            <input type="text" id="procudureName" name="name" class="form-control validate[required],ajax[ajaxProcudureNameCallPhp]">

        </div>
        <div class="col-sm-2"><input type="button" id="checkproc" class="btn btn-info" value="Check"></div>
    </div>


    <div class="form-group pull-right">
        <div class="col-sm-12 dialog-button">
            <input id="saveButton" type="submit" disabled name="Submitx" data-val="<?php echo "Save" ?>"
                   value="<?php echo $save; ?>"
                   class="btn btn-rounded btn-success">
            <input type="button" value="<?php echo $Language->translate("Cancel"); ?>" onclick="closeDialog(this)"
                   class="btn btn-danger btn-rounded">
        </div>
    </div>
</form>
<script type="text/javascript">
    $(function () {
        $('#reportData-form').validationEngine();

        $('#checkproc').on('click', function () {

           // debugger;
          //  if( $('#reportData-form').validationEngine('validate')) {
                //debugger;
                $.ajax({
                    type: "POST",
                    url: BASE_URL + 'ReportData/CheckProcudure',
                    data: {"procudureName": $('#procudureName').val()},
                    success: function (data) {

                        var procwithParam = '';

                        $('.param-lists').remove();
                        $('.no-found').remove();

                        $('input[type=submit]').prop('disabled', true)


                        data = $.parseJSON(data);
                        $.each(data, function (i, item) {

                            var disabled = ''
                            var  validate = 'validate[custom[parameterwithNoValue]]';

                            if (item.toLowerCase().indexOf('date') != -1) {
                                disabled = 'readonly'
                                validate = '';
                            }

                            procwithParam += '<div class="form-group param-lists"><div class="col-sm-3"> <label class="control-label">' + item + '</label></div>'

                            procwithParam += '<div class="col-sm-7"> <input ' + disabled + ' name="params[]" type="text" value="' + item + '"  class="form-control '+validate+'"></div></div>'

                        });

                        $(procwithParam).insertAfter($('#checkproc').parents('.form-group'));

                        if (data != '') {

                            $('input[type=submit]').prop('disabled', false)

                        } else {

                            procwithParam = '<div class="no-found" style="color: red"><b>No such procudured found</b></div>';

                            $(procwithParam).insertAfter($('#checkproc').parents('.form-group'));
                        }

                    }
                });
          //  }
        })



        $('#procudureName').on('keyup', function () {
            $('input[type=submit]').prop('disabled', true)
        })


        $('#saveButton').on('submit', function(){

            $('input[type=submit]').prop('disabled', true);
        })

    });

</script>