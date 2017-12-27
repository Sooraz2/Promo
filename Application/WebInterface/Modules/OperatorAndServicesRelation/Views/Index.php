<?php
/**
 *
 * @var $Language \Language\English\English
 */

$baseUrl = BASE_URL;

$Breadcrumb = "<li><a href='{$baseUrl}'>{$Language->translate('Home')}</a></li>
<li class='active'>{$Language->translate('Operators & Services  Relation')}</li>";

$PageTitle = $Language->translate('Operators & Services Relation');

$PageLeftHeader = "";

include_once SHARED_VIEW . "base.php";

?>
    <div class="maincontent">
        <div class="contentinner">
            <div class="row-fluid">
                <div class="clearfix"></div>

                <div class="content-wrapper">
                    <h3 style="float: left">
                        <?php echo  $Language->translate("Add Operators & Services  Relation") ?>
                    </h3>
                    <div class="overview-head" style="float: right; margin-bottom: 10px">
                        <div class="pull-right">
                            <button type="button" class="btn btn-success"
                                    onclick='showAddNewForm("<?php echo  $Language->translate("Add New Relation") ?>","<?php echo  BASE_URL ?>OperatorAndServicesRelation/Form",400,270)'>
                                <small class="glyphicon glyphicon-plus-sign"></small>
                                <?php echo  $Language->translate("Add New Relation") ?>
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <table id="operator-relation-list" class="table table-bordered table-sorted">
                        <thead>
                        <tr>
                            <th><a class="table-header" field-name="CountryName"><?php echo  $Language->translate("Country") ?></a></th>
                            <th><a class="table-header" field-name="OperatorName"><?php echo  $Language->translate("Operator") ?></a></th>
                            <th><a class="table-header" field-name="ServiceName"><?php echo  $Language->translate("Service") ?></a></th>
                            <th><?php echo  $Language->translate("Action") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                  <div id="loading-msg-user" class="loading-image"></div>
                    <div class="clearfix"></div>


                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $(function () {

            $(".leftmenu").find(".active").removeClass("active");
            $('#menu-OperatorAndServicesRelation').addClass('active');

            $('#operator-relation-list').ajaxGrid({
                pageSize: 20,
                defaultSortExpression: 'ID',
                defaultSortOrder: 'DESC',
                tableHeading: '.table-header',
                url: '<?php echo  BASE_URL ?>OperatorAndServicesRelation/ListAll',
                requestType: 'get',
                loadingImage: $('#loading-msg-user'),

                postContent: [

                    {
                        control: $('<button name ="EditUser"   type="button" title="<?php echo $Language->Translate("Edit")?>" class="btn btn-rounded btn-info" onclick=\'showEditForm(this,' +
                        '"<?php echo $Language->translate("Edit Relation")?>","<?php echo  BASE_URL?>OperatorAndServicesRelation/Form",400,270)\'>' +
                        '<small class="glyphicon glyphicon-pencil"></small>' + '</button>')
                    },

                    {
                        control: $("<form style='display: inline-block' action='<?php echo  BASE_URL?>OperatorAndServicesRelation/Delete' method='POST'>" +
                        "<input type='hidden' name='ID' id='ID' /> " +
                        '<button name="DeleteUser" type="submit" class="btn btn-rounded btn-danger" title="<?php echo $Language->Translate("Delete")?>" onclick=\'return Confirmation(this,"<?php echo $Language->translate("Delete")?>","<?php echo  $Language->translate("Are you sure you want to <b>Delete</b>?") ?>", "<?php echo $Language->translate("Yes")?>", "<?php echo $Language->translate("No")?>")\'>' +
                        '<span class="glyphicon glyphicon-trash"></span></button></form>'),

                        properties: [
                            {
                                propertyField: 'input[type=hidden]#ID',
                                property: 'value',
                                propertyValue: 'ID'
                            }
                        ],

                    }

                ],
                id: 'ID',
                NoRecordsFound: '<?php echo $Language->translate("No Records Found")?>',
                Previous: '<?php echo $Language->translate("Previous")?>',
                Next: '<?php echo $Language->translate("Next")?>'
            });





        });

        $('.post-control-wrapper').hide();
    </script>
<?php include_once SHARED_VIEW . "footer.php"; ?>