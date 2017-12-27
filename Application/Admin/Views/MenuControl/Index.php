<?php
/**
 *
 * @var $Language \Language\English\English
 */

$baseUrl = BASE_URL;

$Breadcrumb = "<li><a href='{$baseUrl}'>{$Language->translate('Home')}</a></li>
<li class='active'>{$Language->translate('Access Control')}</li>";

$PageTitle = $Language->translate('Access Control');

$PageLeftHeader = "";

include_once SHARED_VIEW . "base.php";

?>
    <div class="content-wrapper">
        <div class="page-header">

            <div class="pull-left">
                <form action="#">
                    <select name="AccessLevel" id="AccessLevel">
                        <option value="Moderator"
                                <?php if ($AccessLevel == "Moderator"): ?>selected<?php endif; ?>><?php echo  $Language->translate("Moderator") ?></option>
                        <!--<option value="CustomerCare"
                                <?php /*if ($AccessLevel == "CustomerCare"): */?>selected<?php /*endif; */?>><?php /*echo  $Language->translate("Customer Care") */?></option>-->
                    </select>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>


        <table id="MenuControl_list" class="table table-bordered">
            <thead>
            <tr>
                <?php if (isset($_COOKIE[\Infrastructure\CookieVariable::$BalancePlusLanguage])&&$_COOKIE[\Infrastructure\CookieVariable::$BalancePlusLanguage] == "English"): ?>
                    <th><a class="table-header" field-name="Menu"><?php echo  $Language->translate("UI Page") ?></a></th>
                <?php else: ?>
                    <th><a class="table-header" field-name="MenuRu"><?php echo  $Language->translate("UI Page") ?></a></th>

                <?php endif ?>
                <th><a class="table-header" field-name="UserType"><?php echo  $Language->translate("User Type") ?></a></th>
                <th class="col-xs-1"><?php echo  $Language->translate("Access") ?></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" style="text-align: right">
                    <form method="post" action="">
                        <input type="hidden" id="MenuAccessData" name="MenuAccessData">
                        <input type="hidden" id="AccessLevelHidden" name="AccessLevelHidden">
                        <button type="submit" class="btn btn-success" name="MenuControlSubmit"
                                onclick="setMenuData();">
                            <small class="glyphicon glyphicon-thumbs-up"></small>
                            <?php echo  $Language->translate("Apply") ?>
                        </button>
                    </form>
                </td>
            </tr>
            </tfoot>
        </table>
        <div id="loading-msg-countries" class="loading-image"></div>
        <div class="clearfix"></div>
    </div>

    <script>
        function setMenuData() {
            var menuItemsValues = [];


            $('#MenuControl_list tr td').find('input[type=checkbox]').each(function () {

                var menuId = $.trim($(this).closest('tr').find('#ID').val());

                var menuStatus = $(this).is(':checked');

                menuItemsValues.push({menuId: menuId, isChecked: menuStatus});
            });

            $('#MenuAccessData').val(JSON.stringify(menuItemsValues));

            $('#AccessLevelHidden').val($('#AccessLevel').val());
        }

        function ChangeAccessRights(select) {
            var userType = $(select).attr("rel");
            var id = $(select).siblings("input[type=hidden]#ID").val();
            $.ajax({
                url: "<?php echo  BASE_URL ?>/Admin/MenuControl/ChangeMenuAccess",
                cache: false,
                type: "POST",
                dataType: "json",
                data: {
                    userType: userType,
                    ID: id,
                    changeTo: $(select).is(":checked") ? 1 : 0
                },
                beforeSend: function () {
                    $(select).hide();
                    $(select).parent().append($("<div class='loading'/>"));
                },
                success: function (data) {
                    if (data.success == true) {
                        $(select).show();
                        $(select).siblings("div.loading").remove();
                    }
                }
            });
        }
        $(function () {

            var $accessLevel = $("#AccessLevel");

            $accessLevel.change(function () {
                $('#MenuControl_list').trigger('refreshGrid', {
                    AccessLevel: $("#AccessLevel").val()
                });
            });

            $('.leftmenu').find('.active').removeClass('active');
            $('#menu-UserManagement').addClass('active');
            $('#menu-MenuControl').addClass('active');
            $('#menu-UserManagement').children("ul").css('display', 'block');

            $('#MenuControl_list').ajaxGrid({
                pageSize: 500,
                defaultSortExpression: 'ID',
                defaultSortOrder: 'asc',
                tableHeading: '.table-header',
                url: '<?php echo  BASE_URL?>/Admin/MenuControl/List',
                requestType: 'get',
                loadingImage: $('#loading-msg-countries'),
                filterData: {
                    AccessLevel: $accessLevel.val()
                },
                postContent: [
                    {
                        control: $("<form style='display: inline-block' action='<?php echo  BASE_URL?>/Admin/MenuControl/Delete' method='POST'>" +
                        "<input type='hidden' name='ID' id='ID' /> " +
                        "<input type='checkbox' name='access' id='Access'/> " +
                        '</form>'),
                        properties: [
                            {
                                propertyField: 'input[type=hidden]#ID',
                                property: 'value',
                                propertyValue: 'ID'
                            },
                            {
                                propertyField: 'input[type=checkbox]#Access',
                                property: 'value',
                                propertyValue: 'Access'
                            },
                            {
                                propertyField: 'input[type=checkbox]#Access',
                                property: 'rel',
                                propertyValue: 'UserType'
                            }

                        ]
                    }
                ],
                id: 'ID',
                afterAjaxCallComplete: function () {
                    $("input[type='checkbox']").each(function () {
                        if ($(this).val() == 1) {
                            $(this).prop("checked", true);
                        } else {
                            $(this).prop("checked", false);
                        }
                    });
                },
                NoRecordsFound: 'No Records Found'
            });
        });

    </script>
<?php include_once SHARED_VIEW . "footer.php"; ?>