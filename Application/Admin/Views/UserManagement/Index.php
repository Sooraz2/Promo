<?php
/**
 *
 * @var $Language \Language\English\English
 */

$baseUrl = BASE_URL;

$Breadcrumb = "<li><a href='{$baseUrl}'>{$Language->translate('Home')}</a></li>
<li class='active'>{$Language->translate('User Management')}</li>";

$PageTitle = $Language->translate('User Management');

$PageLeftHeader = "";

include_once SHARED_VIEW . "base.php";

?>
    <div class="maincontent">
        <div class="contentinner">
            <div class="row-fluid">
                <div class="clearfix"></div>

                <div class="content-wrapper">
                    <div class="overview-head">
                        <div class="pull-right">
                            <button type="button" class="btn btn-success"
                                    onclick='showAddNewForm("<?php echo  $Language->translate("Add New User") ?>","<?php echo  BASE_URL ?>Admin/UserManagement/Form",400,470)'>
                                <small class="glyphicon glyphicon-plus-sign"></small>
                                <?php echo  $Language->translate("Add New User") ?>
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <table id="user-list" class="table table-bordered table-sorted">
                        <thead>
                        <tr>
                            <th><a class="table-header" field-name="DateCreated"><?php echo  $Language->translate("Date") ?></a>
                            </th>
                            <th><a class="table-header"
                                   field-name="Username"><?php echo  $Language->translate("Username") ?></a>
                            </th>
                            <th><a class="table-header" field-name="Email"><?php echo  $Language->translate("Email") ?></a></th>
                            <th><a class="table-header" field-name="Name"><?php echo  $Language->Name2 ?></a></th>
                            <th><a class="table-header"
                                   field-name="UserTypeShow"><?php echo  $Language->translate("Role") ?></a>
                            </th>
                            <th><?php echo  $Language->translate("Action") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div id="loading-msg-user" class="loading-image"></div>
                    <div class="clearfix"></div>
                    <h3>
                        <?php echo  $Language->translate("User Actions") ?>
                    </h3>

                    <table class="table table-bordered dyntable table-sorted" id="user-log-list">
                        <thead>
                        <tr>
                            <?php if (isset($UserType) && $UserType != "" && $UserType == 4): ?>
                                <th width="215"><a class="table-header"
                                       field-name="Ip"><?php echo  $Language->translate("Username/IP") ?></a>
                                </th>
                            <?php else: ?>
                                <th width="215"><a class="table-header"
                                       field-name="Username"><?php echo  $Language->translate("Username/IP") ?></a>
                                </th>
                            <?php endif ?>
                            <th width="300"><a class="table-header"
                                   field-name="Datetime"><?php echo  $Language->translate("DateTime") ?></a>
                            </th>
                            <th><a class="table-header"
                                   field-name="Action"><?php echo  $Language->translate("User Actions") ?></a>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div id="loading-msg-user-log" class="loading-image"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $(function () {

            $(".leftmenu").find(".active").removeClass("active");
            $('#menu-UserManagement').addClass('active');
            $('#submenu-UserManagement').addClass('active');
            $('#menu-UserManagement').children("ul").css('display', 'block');

            $('#user-list').ajaxGrid({
                pageSize: 5,
                defaultSortExpression: 'ID',
                defaultSortOrder: 'desc',
                tableHeading: '.table-header',
                url: '<?php echo  BASE_URL ?>Admin/UserManagement/List',
                requestType: 'get',
                loadingImage: $('#loading-msg-user'),
                contentAdditionalProperty: [

                    {
                        name: 'Username',
                        control: $('<form action="" method="POST" id="UserLogSubmitForm">' +
                        '<input type="hidden" name="UserID" id="UserID">' +
                        '<input class="btn-link" type="submit"  name="UserLogSubmit" id="UserLogSubmit"/></form>'),
                        properties: [
                            {field: 'input[type=hidden]#UserID', value: 'ID'},
                            {field: 'input[type=submit]#UserLogSubmit', value: 'Username'}
                        ]
                    }
                ],
                postContent: [
                    <?php if( $_SESSION["UserType"]==1 ):?>
                    {
                        control: $('<button name ="EditUser"   type="button" title="<?php echo $Language->Translate("Edit")?>" class="btn btn-rounded btn-info" onclick=\'showEditForm(this,' +
                        '"<?php echo $Language->translate("Edit")?>","<?php echo  BASE_URL?>Admin/UserManagement/Form",400,490)\'>' +
                        '<small class="glyphicon glyphicon-pencil"></small>' + '</button>')
                    },
                    <?php else:?>
                    {
                        control: $('<button  name ="EditUser"  type="button" title="<?php echo $Language->Translate("Edit")?>" class="btn btn-rounded btn-info" onclick=\'showEditForm(this,' +
                        '"<?php echo $Language->translate("Edit")?>","<?php echo  BASE_URL?>Admin/UserManagement/Form",400,490)\'>' +
                        '<small class="glyphicon glyphicon-pencil"></small></button>'),
                        properties: [],
                        removeWhen: {property: 'UserType', value: ['1']}
                    },
                    <?php endif;?>
                    <?php if( $_SESSION["UserType"]==1 ):?>
                    {
                        control: $("<form style='display: inline-block' action='<?php echo  BASE_URL?>Admin/UserManagement/Delete' method='POST'>" +
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
                        removeWhen: {property: 'ID', value: ["1", '<?php echo  $_SESSION["UserID"] ?>'], WrapperWidth: '46px'}
                    }

                    <?php endif;?>
                ],
                id: 'ID',
                NoRecordsFound: '<?php echo $Language->translate("No Records Found")?>',
                Previous: '<?php echo $Language->translate("Previous")?>',
                Next: '<?php echo $Language->translate("Next")?>'
            });

            $('#user-log-list').ajaxGrid({
                pageSize: 10,
                defaultSortExpression: 'LoginUserLogID',
                defaultSortOrder: 'desc',
                tableHeading: '.table-header',
                url: '<?php echo  BASE_URL ?>Admin/UserLog/List',
                requestType: 'get',
                loadingImage: $('#loading-msg-user-log'),
                id: 'LoginUserLogID',
                filterData: {
                    id: <?php if(isset($userId)): echo $userId; else: echo 0; endif;?>
                },
                NoRecordsFound: '<?php echo $Language->translate("No Records Found")?>',
                Previous: '<?php echo $Language->translate("Previous")?>',
                Next: '<?php echo $Language->translate("Next")?>'
            });

        });

        $('.post-control-wrapper').hide();
    </script>
<?php include_once SHARED_VIEW . "footer.php"; ?>