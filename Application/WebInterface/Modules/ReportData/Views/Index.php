<?php
/**
 *
 * @var $Language \Language\English\English
 */

$baseUrl = BASE_URL;

$Breadcrumb = "<li><a href='{$baseUrl}'>{$Language->translate('Home')}</a></li>
<li class='active'>{$Language->translate('Report Data')}</li>";

$PageTitle = $Language->translate('Report Data');

$PageLeftHeader = "";

include_once SHARED_VIEW . "base.php";

?>
    <div class="maincontent">
        <div class="contentinner">
            <div class="row-fluid">
                <div class="clearfix"></div>

                <div class="content-wrapper">
                    <h3 style="float: left">
                        <?php echo  $Language->translate("Add New Report") ?>
                    </h3>
                    <div class="overview-head" style="float: right; margin-bottom: 10px">
                        <div class="pull-right">
                            <button type="button" class="btn btn-success"
                                    onclick='showAddNewForm("<?php echo  $Language->translate("Add New Report") ?>","<?php echo  BASE_URL ?>ReportData/Form",600,170)'>
                                <small class="glyphicon glyphicon-plus-sign"></small>
                                <?php echo  $Language->translate("Add New Report") ?>
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <table id="report-data-list" class="table table-bordered table-sorted">
                        <thead>
                        <tr>
                            <th><a class="table-header" field-name="name"><?php echo  $Language->translate("Stored Procudure") ?></a></th>
                            <th><a class="table-header" field-name="country"><?php echo  $Language->translate("Country") ?></a></th>
                            <th><a class="table-header" field-name="operator"><?php echo  $Language->translate("Operator") ?></a></th>
                            <th><a class="table-header" field-name="service"><?php echo  $Language->translate("Service") ?></a></th>
                            <th><a class="table-header" field-name="param"><?php echo  $Language->translate("With Params") ?></a></th>
                            <th>Action</th>

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
            $('#menu-ReportData').addClass('active');

            $('#report-data-list').ajaxGrid({
                pageSize: 20,
                defaultSortExpression: 'ID',
                defaultSortOrder: 'DESC',
                tableHeading: '.table-header',
                url: '<?php echo  BASE_URL ?>ReportData/GetAll',
                requestType: 'get',
                loadingImage: $('#loading-msg-user'),

                postContent: [

                    {
                        control: $("<form style='display: inline-block' action='<?php echo  BASE_URL?>ReportData/Delete' method='POST'>" +
                        "<input type='hidden' name='ID' id='ID' /> " +
                        '<button name="DeleteUser" type="submit" class="btn btn-rounded btn-danger" title="<?php echo $Language->Translate("Delete")?>" onclick=\'return Confirmation(this,"<?php echo $Language->translate("Delete")?>","<?php echo  $Language->translate("Are you sure you want to <b>Delete</b>?") ?>", "<?php echo $Language->translate("Yes")?>", "<?php echo $Language->translate("No")?>")\'>' +
                        '<span class="glyphicon glyphicon-trash"></span></button></form>'),

                        properties: [
                            {
                                propertyField: 'input[type=hidden]#ID',
                                property: 'value',
                                propertyValue: 'id'
                            }
                        ],

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