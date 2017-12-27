<?php
/**
 *
 * @var $Language \Language\English\English
 */

$baseUrl = BASE_URL;

$Breadcrumb = "<li><a href='{$baseUrl}ActiveTeasers'>{$Language->translate('Home')}</a></li>
<li class='active'>{$Language->translate('Logs')}</li>";

$PageTitle = $Language->translate('Logs');

$PageLeftHeader = "";

include_once SHARED_VIEW . "base.php";

?>
    <div class="maincontent">
        <div class="contentinner">
            <div class="row-fluid">
                <div class="clearfix"></div>

                <div class="content-wrapper">
                    <h3>
                        <?php echo $Language->translate("Logs")?>
                    </h3>

                    <table class="table table-bordered dyntable" id="user-log-list">
                        <thead>
                        <tr>
                            <th style="width: 100px;"><a class="table-header"  field-name="Username"><?php echo $Language->translate("Username")?></a></th>
                            <th style="width: 150px;"><a class="table-header" field-name="Datetime"><?php echo $Language->translate("DateTime")?></a></th>
                            <th class="Action"><a class="table-header" field-name="Action"><?php echo $Language->translate("User Actions")?></a></th>
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
            $('#menu-Logs').addClass('active');

            $('#user-log-list').ajaxGrid({
                pageSize: 10,
                defaultSortExpression: 'LoginUserLogID',
                defaultSortOrder: 'desc',
                tableHeading: '.table-header',
                url: '<?php echo  BASE_URL ?>Logs/List',
                requestType: 'get',
                loadingImage: $('#loading-msg-user-log'),
                id: 'LoginUserLogID',
                filterData: {
                    id: <?php echo  $userId?>
                },
                NoRecordsFound: '<?php echo $Language->translate("No Records Found")?>',
                Previous: '<?php echo $Language->translate("Previous")?>',
                Next: '<?php echo $Language->translate("Next")?>'
            });

        });
    </script>
<?php include_once SHARED_VIEW . "footer.php"; ?>