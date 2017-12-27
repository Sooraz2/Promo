<?php
/**
 *
 * @var $Language \Language\English\English
 * @var $interactivity_activation \WebInterface\Models\InteractivityActivation
 */

?>

<form action="<?php echo BASE_URL . "OperatorAndServicesRelation/SaveUpdate" ?>" id="relation-form" method="post"
      class="form-horizontal">

    <input type="hidden" id="ID" name="ID" value="<?php echo $ID ?>">

    <div class="form-group">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Country") ?></label>
        </div>
        <div class="col-sm-8">
            <div i data-relation="first" class="date-container">
                <select name="Country" class="form-control validate[required]">
                    <?php

                    foreach($AllCountry as $countryList){
                        $selected = '';
                        if($Country==$countryList['id']) { $selected = "selected"; }

                        echo "<option ".$selected." value='".$countryList['id']."'>".$countryList['name']."</option>";

                    } ?>
                    </select>
            </div>

        </div>
    </div>



    <div class="form-group">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Operator") ?></label>
        </div>
        <div class="col-sm-8">
            <div  data-relation="first" class="date-container">
                <select name="Operator" class="form-control validate[required]">
                    <?php

                    foreach($AllOperator as $OperatorList){
                        $selected = '';
                        if($Operator==$OperatorList['id']) { $selected = "selected"; }

                        echo "<option ".$selected." value='".$OperatorList['id']."'>".$OperatorList['name']."</option>";

                    } ?>
                </select>
            </div>

        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-4">
            <label class="control-label"><?php echo $Language->translate("Service") ?></label>
        </div>
        <div class="col-sm-8">
            <div  data-relation="first" class="date-container">
                <select name="Service" class="form-control validate[required]">
                    <?php

                    foreach($AllService as $ServiceList){
                        $selected = '';
                        if($Service==$ServiceList['id']) { $selected = "selected"; }

                        echo "<option ".$selected." value='".$ServiceList['id']."'>".$ServiceList['name']."</option>";

                    } ?>
                </select>
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
        $('#relation-form').validationEngine();

    });

</script>