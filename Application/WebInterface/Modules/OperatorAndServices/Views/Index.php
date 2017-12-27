<?php
/**
 *
 * @var $Language \Language\English\English
 */

$baseUrl = BASE_URL;

$Breadcrumb = "<li><a href='{$baseUrl}'>{$Language->translate('Home')}</a></li>
<li class='active'>{$Language->translate('Operators & Services')}</li>";

$PageTitle = $Language->translate('Operators & Services');

$PageLeftHeader = "";

include_once SHARED_VIEW . "base.php";

?>
    <div class="maincontent">
        <div class="contentinner">
            <div class="row-fluid">
                <div class="clearfix"></div>

                <div class="content-wrapper">
                    <h3 style="float: left">
                        <?php echo  $Language->translate("Add Country") ?>
                    </h3>
                    <div class="overview-head" style="float: right; margin-bottom: 10px">
                        <div class="pull-right">
                            <button type="button" class="btn btn-success"
                                    onclick='showAddNewForm("<?php echo  $Language->translate("Add New Country") ?>","<?php echo  BASE_URL ?>Country/Form",400,170)'>
                                <small class="glyphicon glyphicon-plus-sign"></small>
                                <?php echo  $Language->translate("Add New Country") ?>
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <table id="country-list" class="table table-bordered table-sorted">
                        <thead>
                        <tr>
                            <th><a class="table-header" field-name="name"><?php echo  $Language->translate("Country") ?></a>  </th>
                            <th width="400px"><?php echo  $Language->translate("Action") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                   <div id="country-list-loader" class="loading-image"></div>
                    <div class="clearfix"></div>



                    <h3 style="float: left">
                        <?php echo  $Language->translate("Add Operator") ?>
                    </h3>
                    <div class="overview-head" style="float: right; margin-bottom: 10px">
                        <div class="pull-right">
                            <button type="button" class="btn btn-success"
                                    onclick='showAddNewForm("<?php echo  $Language->translate("Add New Operator") ?>","<?php echo  BASE_URL ?>Operator/Form",400,170)'>
                                <small class="glyphicon glyphicon-plus-sign"></small>
                                <?php echo  $Language->translate("Add New Operator") ?>
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <table class="table table-bordered dyntable table-sorted" id="operator-list">
                        <thead>
                        <tr>

                                <th><a class="table-header"
                                       field-name="name"><?php echo  $Language->translate("Operator") ?></a>
                                </th>
                            <th width="400px"><?php echo  $Language->translate("Action") ?></th>

                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div id="operator-list-loader" class="loading-image"></div>
                    <div class="clearfix"></div>


                    <h3 style="float: left">
                        <?php echo  $Language->translate("Add Services") ?>
                    </h3>
                    <div class="overview-head" style="float: right; margin-bottom: 10px">
                        <div class="pull-right">
                            <button type="button" class="btn btn-success"
                                    onclick='showAddNewForm("<?php echo  $Language->translate("Add New Services") ?>","<?php echo  BASE_URL ?>Service/Form",400,170)'>
                                <small class="glyphicon glyphicon-plus-sign"></small>
                                <?php echo  $Language->translate("Add New Service") ?>
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <table class="table table-bordered dyntable table-sorted" id="service-list">
                        <thead>
                        <tr>

                        <th><a class="table-header" field-name="name"><?php echo  $Language->translate("Services") ?></a></th>

                            <th width="400px"><?php echo  $Language->translate("Action") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                  <div id="service-list-loader" class="loading-image"></div>


                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $(function () {

            $(".leftmenu").find(".active").removeClass("active");
            $('#menu-OperatorAndServices').addClass('active');


            $('#country-list').ajaxGrid({
                pageSize: 5,
                defaultSortExpression: 'name',
                defaultSortOrder: 'ASC',
                tableHeading: '.table-header',
                url: '<?php echo  BASE_URL ?>Country/ListAll',
                requestType: 'get',
                loadingImage: $('#country-list-loader'),

                postContent: [

                    {
                        control: $('<button name ="EditUser"   type="button" title="<?php echo $Language->Translate("Edit")?>" class="btn btn-rounded btn-info" onclick=\'showEditForm(this,' +
                        '"<?php echo $Language->translate("Edit")?>","<?php echo  BASE_URL?>Country/Form",400,190)\'>' +
                        '<small class="glyphicon glyphicon-pencil"></small>' + '</button>')
                    },

                    {
                        control: $("<form style='display: inline-block' action='<?php echo  BASE_URL?>Country/Delete' method='POST'>" +
                        "<input type='hidden' name='ID' id='ID' /> " +
                        '<button name="DeleteUser" type="submit" class="btn btn-rounded btn-danger" title="<?php echo $Language->Translate("Delete")?>" onclick=\'return Confirmation(this,"<?php echo $Language->translate("Delete")?>","<?php echo  $Language->translate(" It will delete corresponding RELATION also.<br> Are you sure you want to <b>Delete</b>?") ?>", "<?php echo $Language->translate("Yes")?>", "<?php echo $Language->translate("No")?>")\'>' +
                        '<span class="glyphicon glyphicon-trash"></span></button></form>'),

                        properties: [
                            {
                                propertyField: 'input[type=hidden]#ID',
                                property: 'value',
                                propertyValue: 'id'
                            }
                        ]

                    }

                ],
                id: 'id',
                NoRecordsFound: '<?php echo $Language->translate("No Records Found")?>',
                Previous: '<?php echo $Language->translate("Previous")?>',
                Next: '<?php echo $Language->translate("Next")?>'
            });




            $('#operator-list').ajaxGrid({
                pageSize: 5,
                defaultSortExpression: 'name',
                defaultSortOrder: 'ASC',
                tableHeading: '.table-header',
                url: '<?php echo  BASE_URL ?>Operator/ListAll',
                requestType: 'get',
                loadingImage: $('#operator-list-loader'),

                postContent: [

                    {
                        control: $('<button name ="EditUser"   type="button" title="<?php echo $Language->Translate("Edit")?>" class="btn btn-rounded btn-info" onclick=\'showEditForm(this,' +
                            '"<?php echo $Language->translate("Edit")?>","<?php echo  BASE_URL?>Operator/Form",400,190)\'>' +
                            '<small class="glyphicon glyphicon-pencil"></small>' + '</button>')
                    },

                    {
                        control: $("<form style='display: inline-block' action='<?php echo  BASE_URL?>Operator/Delete' method='POST'>" +
                            "<input type='hidden' name='ID' id='ID' /> " +
                            '<button name="DeleteUser" type="submit" class="btn btn-rounded btn-danger" title="<?php echo $Language->Translate("Delete")?>" onclick=\'return Confirmation(this,"<?php echo $Language->translate("Delete")?>","<?php echo  $Language->translate("It will delete corresponding RELATION also.<br> Are you sure you want to <b>Delete</b>?") ?>", "<?php echo $Language->translate("Yes")?>", "<?php echo $Language->translate("No")?>")\'>' +
                            '<span class="glyphicon glyphicon-trash"></span></button></form>'),

                        properties: [
                            {
                                propertyField: 'input[type=hidden]#ID',
                                property: 'value',
                                propertyValue: 'id'
                            }
                        ]

                    }

                ],
                id: 'id',
                NoRecordsFound: '<?php echo $Language->translate("No Records Found")?>',
                Previous: '<?php echo $Language->translate("Previous")?>',
                Next: '<?php echo $Language->translate("Next")?>'
            });




            $('#service-list').ajaxGrid({
                pageSize: 5,
                defaultSortExpression: 'name',
                defaultSortOrder: 'ASC',
                tableHeading: '.table-header',
                url: '<?php echo  BASE_URL ?>Service/ListAll',
                requestType: 'get',
                loadingImage: $('#service-list-loader'),

                postContent: [

                    {
                        control: $('<button name ="EditUser"   type="button" title="<?php echo $Language->Translate("Edit")?>" class="btn btn-rounded btn-info" onclick=\'showEditForm(this,' +
                            '"<?php echo $Language->translate("Edit")?>","<?php echo  BASE_URL?>Service/Form",400,190)\'>' +
                            '<small class="glyphicon glyphicon-pencil"></small>' + '</button>')
                    },

                    {
                        control: $("<form style='display: inline-block' action='<?php echo  BASE_URL?>Service/Delete' method='POST'>" +
                            "<input type='hidden' name='ID' id='ID' /> " +
                            '<button name="DeleteUser" type="submit" class="btn btn-rounded btn-danger" title="<?php echo $Language->Translate("Delete")?>" onclick=\'return Confirmation(this,"<?php echo $Language->translate("Delete")?>","<?php echo  $Language->translate("It will delete corresponding RELATION also.<br> Are you sure you want to <b>Delete</b>?") ?>", "<?php echo $Language->translate("Yes")?>", "<?php echo $Language->translate("No")?>")\'>' +
                            '<span class="glyphicon glyphicon-trash"></span></button></form>'),

                        properties: [
                            {
                                propertyField: 'input[type=hidden]#ID',
                                property: 'value',
                                propertyValue: 'id'
                            }
                        ]

                    }

                ],
                id: 'id',
                NoRecordsFound: '<?php echo $Language->translate("No Records Found")?>',
                Previous: '<?php echo $Language->translate("Previous")?>',
                Next: '<?php echo $Language->translate("Next")?>'
            });




        });

        $('.post-control-wrapper').hide();
    </script>
<?php include_once SHARED_VIEW . "footer.php"; ?>