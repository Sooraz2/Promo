</div>
<!--row-fluid-->
</div>
<!--contentinner-->
</div>
<!--maincontent-->

</div>
<!--mainright-->
<!-- END OF RIGHT PANEL -->

<div class="clearfix"></div>


<div class="footer">
    <div class="footerleft">Unifun Promo</div>
    <div class="footerright" style="margin-left: 210px;">&copy; Unifun</div>
</div>
<!--footer-->
</div>
<!--mainwrapper-->

</body>

<script type="text/javascript">
    var showHideStatus;

    $(function () {
        $('.showmenu').click(function () {

            if ($('.leftpanel').hasClass('hide')) {
                showHideStatus = "Show";
            }
            else {
                showHideStatus = "Hide";
            }

            $.ajax({
                url: "<?php echo BASE_URL?>Shared/ShowHideLeftPanel",
                type: "GET",
                dataType: "json",
                data: {ShowHideLeftPanel: showHideStatus}
            });

            if ($('.leftpanel').hasClass('hide')) {
                ShowLeftPanel();
            }
            else {
                HideLeftPanel();
            }
            return false;

        });
    });

    function ShowLeftPanel() {
        jQuery('.leftpanel').css({marginLeft: '0px'}).removeClass('hide');
        jQuery('.rightpanel').css({marginLeft: '260px'});
        jQuery('.mainwrapper').css({backgroundPosition: '0 0'});
        jQuery('.footerleft').show();
        jQuery('.footerright').css({marginLeft: '260px'});
        jQuery('.mainwrapper').css('min-width', $(".rightpanel").width() + 260 + "px");
        sessionStorage.setItem("ShowHideLeftPanel", "Show");
    }

    function HideLeftPanel() {
        jQuery('.leftpanel').css({marginLeft: '-260px'}).addClass('hide');
        jQuery('.rightpanel').css({marginLeft: 0});
        jQuery('.mainwrapper').css({backgroundPosition: '-260px 0'});
        jQuery('.footerleft').hide();
        jQuery('.footerright').css({marginLeft: 0});
        jQuery('.mainwrapper').css('min-width', "1007px");
        sessionStorage.setItem("ShowHideLeftPanel", "Hide");
    }

</script>

<script type="text/javascript">
    <?php if (isset($_SESSION["ShowHideLeftPanel"]) && $_SESSION["ShowHideLeftPanel"] != "" and $_SESSION["ShowHideLeftPanel"] != null): ?>
    showHideStatus = '<?php echo $_SESSION["ShowHideLeftPanel"]?>';

    <?php else:?>

    showHideStatus = 'Show';

    <?php endif;?>


    if (showHideStatus == "Show") {
        ShowLeftPanel();
    } else {
        HideLeftPanel();
    }

        jQuery(window).resize(function () {
            if(!jQuery('.mainwrapper').hasClass("no-responsive")) {
                if (showHideStatus == "Show") {
                    jQuery('.mainwrapper').css('min-width', $(".rightpanel").width() + 260 + "px");
                } else {
                    jQuery('.mainwrapper').css('min-width', "1007px");
                }
            }else{

                jQuery('.mainwrapper').css('min-width', "auto");
            }
        });


</script>
</html>




