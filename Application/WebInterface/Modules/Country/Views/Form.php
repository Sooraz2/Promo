<?php
/**
 *
 * @var $Language \Language\English\English
 * @var $interactivity_activation \WebInterface\Models\InteractivityActivation
 */

?>

<form action="<?php echo BASE_URL . "Country/SaveUpdate" ?>" id="country-form" method="post"
      class="form-horizontal">

    <input type="hidden" id="ID" name="id" value="<?php echo $id ?>">

    <div class="form-group">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Name") ?></label>
        </div>
        <div class="col-sm-8">
            <div id="first_date_picker_container" data-relation="first" class="date-container">
                <input type="text" name="name" id="Date-BroadCast" value="<?php echo $name ?>" class="form-control validate[required,ajax[ajaxCountryNameCheck]]">
            </div>

        </div>
    </div>

    <div class="form-group pull-right">
        <div class="col-sm-12 dialog-button">
            <input type="submit" name="Submitx" data-val="<?php echo "Save" ?>"
                   value="<?php echo $save; ?>"
                   class="btn btn-rounded btn-success">
            <input type="button" value="<?php echo $Language->translate("Cancel"); ?>" onclick="closeDialog(this)"
                   class="btn btn-danger btn-rounded">
        </div>
    </div>
</form>
<script type="text/javascript">
    $(function () {
        $('#country-form').validationEngine();

    });

</script>