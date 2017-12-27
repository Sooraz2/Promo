<?php
/**
 *
 * @var $Language \Language\English\English
 */

$baseUrl = BASE_URL;

$Breadcrumb = "<li><a href='{$baseUrl}'>{$Language->translate('Home')}</a></li>
<li class='active'>{$Language->translate('Logs')}</li>";

$PageTitle = $Language->translate('Logs');

$PageLeftHeader ="";

include_once SHARED_VIEW . "base.php";

?>
    <div class="content-wrapper">

    <?php echo  $Language->translate("You are not authorized to view this page.") ?>

    </div>
    <script type="text/javascript">

        $(function () {
            var MenuId = '#menu-<?php echo  $slug ?>';
            $('.leftmenu').find('.active').removeClass('active');
            $(MenuId).addClass('active');
        });
    </script>
<?php include_once SHARED_VIEW . "footer.php"; ?>