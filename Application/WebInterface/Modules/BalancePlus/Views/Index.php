<?php
/**
 *
 * @var $Language \Language\English\English
 */

$baseUrl = BASE_URL;

$Breadcrumb = "<li><a href='{$baseUrl}'>{$Language->translate('Home')}</a></li>
<li class='active'>{$Language->translate('Balance Plus Views')}</li>";

$PageTitle = $Language->translate('Balance Plus Views');

$PageLeftHeader = "";

include_once SHARED_VIEW . "base.php";

?>
    <div class="maincontent">
        <div class="contentinner">
            <div class="row-fluid">
                <div class="clearfix"></div>

                <div class="" style="margin-bottom: 20px">
                    <form method="POST" class="form-inline form-inline-ie" id="Analytics-Form"
                          action="<?php echo BASE_URL . "BalancePlus/Export" ?>">

                        <input type="hidden" id="CountryExport" name="CountryExport">
                        <input type="hidden" id="OperatorExport" name="OperatorExport">
                        <input type="hidden" id="ServiceExport" name="ServiceExport">

                        <div class="imput-append input-group pull-left" style="margin-right: 20px;">
            <span class="input-group-addon" style="color: #2D94DA;"><i class="fa fa-calendar" aria-hidden="true"></i></span>

                            <input class="form-control validate[required]" id="DateFrom" name="DateFrom" type="text"
                                   value=""
                                   style="width: 150px" placeholder="<?php echo $Language->translate("Date From") ?>">
                        </div>

                        <div class="imput-append input-group pull-left" style="margin-right: 20px;">
            <span class="input-group-addon" style="color: #2D94DA;"><i class="fa fa-calendar"
                                                                       aria-hidden="true"></i></span>
                            <input class="form-control validate[required,custom[checkDateOrder]" id="DateTo"
                                   name="DateTo" type="text"
                                   value="" style=" width: 150px"
                                   placeholder="<?php echo $Language->translate("Date To") ?>">
                        </div>

                        <div class="form-group" style="margin-left: 15px">
                            <select class="form-control" name="Country" id="CountryList"
                                    style="width:155px; margin: 0;">
                                <option value="">Select Country</option>
                                <?php foreach ($CountryList as $country) {
                                    echo '<option data-value = "' . $country['name'] . '"  value="' . $country['id'] . '">' . $country['name'] . '</option>';
                                } ?>

                            </select>
                        </div>


                        <div class="form-group" style="margin-left: 15px">
                            <select class="form-control" name="Operator" id="OperatorList"
                                    style="width:155px; margin: 0;">
                                <option value="">Select Operator</option>

                            </select>
                        </div>

                       <div class="form-group" style="margin-left: 15px">
                            <select class="form-control" name="ServiceFilter" id="ServiceList"
                                    style="width:155px; margin: 0;">
                                <option value="">Select Service</option>

                            </select>
                        </div>

                      <!--  <div class="form-group" style="margin-left: 15px">
                            <select class="form-control" name="PromotionList" id="PromotionList"
                                    style="width:155px; margin: 0;">
                                <option value="">Select Promotion</option>
                                <option>Balance Plus</option>
                                <option>IVR Broadcaster</option>
                                <option>SMS Broadcast</option>
                                <option>SNS</option>
                            </select>
                        </div>-->
                        <div class="form-group" style="margin-left: 15px">
                            <button type="button" id="ViewReports" class="btn btn-success">Apply</button>
                        </div>

                        <div class="form-group">
                            <input id="ExportButton" type="submit" style="height: 30px"
                                   class="form-control btn btn-primary" value="Export">
                        </div>
                    </form>
                    <table id="Analytics-Report-List" class="table table-bordered table-sorted">
                        <thead>
                        <tr>
                            <th style="width: 100px"><a class="table-header"
                                                        field-name="Date"><?php echo $Language->translate("Date") ?></a>
                            </th>
                            <th><a class="table-header"
                                   field-name="Country"><?php echo $Language->translate("Country") ?></a></th>
                            <th><a class="table-header"
                                   field-name="Operator"><?php echo $Language->translate("Operator") ?></a></th>
                            <th><a class="table-header"
                                   field-name="Service"><?php echo $Language->translate("Service") ?></a></th>

                            <th><a class="table-header"
                                   field-name="PromotionText"><?php echo $Language->translate("Promo text") ?></a></th>

                            <th><a class="table-header"
                                   field-name="DayName"><?php echo $Language->translate("Day of week") ?></a></th>
                            <th><a class="table-header"
                                   field-name="Views"><?php echo $Language->translate("Views") ?></a></th>
                            <th><a class="table-header"
                                   field-name="UniqueViews"><?php echo $Language->translate("Unique Views") ?></a></th>

                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div id="loading-analytics" class="loading-image"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $(function () {

            $(".leftmenu").find(".active").removeClass("active");

            $('#menu-BalancePlus').addClass('active');


            $("#Analytics-Form").validationEngine();
            var $dateFrom = $('#DateFrom');
            var $dateTo = $('#DateTo');
            var $viewReports = $("#ViewReports");


            var today = moment().toDate();
            var threeMonthsAgo = moment().subtract(3, 'months').toDate();

            $dateFrom.datepicker({
                dateFormat: "yy-mm-dd",
                changeYear: true,
                changeMonth: true,
                maxDate: today,
                minDate: threeMonthsAgo
            });

            $dateTo.datepicker({
                dateFormat: "yy-mm-dd",
                changeYear: true,
                changeMonth: true,
                maxDate: today,
                minDate: threeMonthsAgo,
                hour: 23,
                minute: 59
            });


        });

        var filterValues = '';
        $('#Analytics-Report-List').ajaxGrid({
            pageSize: 20,
            defaultSortExpression: '`Date`',
            defaultSortOrder: 'ASC',
            tableHeading: '.table-header',
            url: '<?php echo  BASE_URL ?>BalancePlus/GetAll',
            filterData: {filterData: filterValues},
            requestType: 'get',
            loadingImage: $('#loading-analytics'),
            id: 'id',
            NoRecordsFound: '<?php echo $Language->translate("No Records Found")?>',
            Previous: '<?php echo $Language->translate("Previous")?>',
            Next: '<?php echo $Language->translate("Next")?>'
        });


        function GetCountryList(Type) {

            var SelectedCountry = '';
            $('#CountryList').next().find('.multi-select-child-ul .multi-select-child-li').each(function (data) {
                if ($(this).find('input').hasClass('multiselect-Selected')) {
                    if (Type == 'Name') {
                        SelectedCountry += "'" + $(this).find('span').text() + "',";
                    } else {
                        SelectedCountry += "'" + $(this).find('span').attr('data-id') + "',";
                    }
                }
            })

            if (SelectedCountry.length > 0) {
                return SelectedCountry.slice(0, -1)
            }
            return SelectedCountry
        }

        function GetOperatorList(Type) {

            var SelectedOperator = '';
            $('#OperatorList').next().find('.multi-select-child-ul .multi-select-child-li').each(function (data) {
                if ($(this).find('input').hasClass('multiselect-Selected')) {
                    if (Type == 'Name') {
                        SelectedOperator += "'" + $(this).find('span').text() + "',";
                    } else {
                        SelectedOperator += "'" + $(this).find('span').attr('data-id') + "',";
                    }
                }
            })

            if (SelectedOperator.length > 0) {
                return SelectedOperator.slice(0, -1)
            }
            return SelectedOperator
        }

        function GetServiceList(Type) {

            var SelectedService = '';
            $('#ServiceList').next().find('.multi-select-child-ul .multi-select-child-li').each(function (data) {
                if ($(this).find('input').hasClass('multiselect-Selected')) {
                    if (Type == 'Name') {
                        SelectedService += "'" + $(this).find('span').text() + "',";
                    } else {
                        SelectedService += "'" + $(this).find('span').attr('data-id') + "',";
                    }
                }
            })

            if (SelectedService.length > 0) {
                return SelectedService.slice(0, -1)

            }
            return SelectedService;
        }


        var CountryList = <?php echo $CountryListAll; ?>;


        $('#CountryList').multiSelect({
            data: CountryList,
            SelectionText: '<?php echo '--'.$Language->translate("Country").'--'?>',
            Level: 1,
            Type: 'name',
            Type2: 'id'
        });

        $('#OperatorList').multiSelect({
            data: '',
            SelectionText: '<?php echo '--'.$Language->translate("Operator").'--'?>',
            Level: 1,
            Type: 'name',
            Type2: 'id'
        });

        $('#ServiceList').multiSelect({
            data: '',
            SelectionText: '<?php echo '--'.$Language->translate("Service").'--'?>',
            Level: 1,
            Type: 'name',
            Type2: 'id'
        });

        $('#CountryList').next('.multi-select-container').find('.multi-select-section').on('mouseleave', function () {

            var CountryListID = GetCountryList('ID');

            if (CountryListID.length > 0) {
                $.ajax({
                    url: "<?php echo BASE_URL ?>BalancePlus/GetOperatorByCountry",
                    type: "post",
                    data: {"CountryID": CountryListID},
                    success: function (response) {
                        $('#OperatorList').next('.multi-select-container').remove();
                        $('#OperatorList').multiSelect({
                            data: JSON.parse(response),
                            SelectionText: '<?php echo '--'.$Language->translate("Operator").'--'?>',
                            Level: 1,
                            Type: 'name',
                            Type2: 'id'
                        });

                        ServiceSelection();
                    }
                })
            } else {
                $('#OperatorList').next('.multi-select-container').remove();
                $('#OperatorList').multiSelect({
                    data: '',
                    SelectionText: '<?php echo '--'.$Language->translate("Operator").'--'?>',
                    Level: 1,
                    Type: 'name',
                    Type2: 'id'
                });
            }


        })


        function ServiceSelection() {

            $('#OperatorList').next('.multi-select-container').find('.multi-select-section').on('mouseleave', function () {

                var OperatorListID = GetOperatorList('ID');

                var CountryListID = GetCountryList('ID');

                if (OperatorListID.length > 0) {
                    $.ajax({
                        url: "<?php echo BASE_URL ?>BalancePlus/GetServiceByOperator",
                        type: "post",
                        data: {"OperatorID": OperatorListID, "CountryID": CountryListID},
                        success: function (response) {
                            $('#ServiceList').next('.multi-select-container').remove();
                            $('#ServiceList').multiSelect({
                                data: JSON.parse(response),
                                SelectionText: '<?php echo '--'.$Language->translate("Service").'--'?>',
                                Level: 1,
                                Type: 'name',
                                Type2: 'id'
                            });
                        }
                    })
                } else {
                    $('#ServiceList').next('.multi-select-container').remove();
                    $('#ServiceList').multiSelect({
                        data: '',
                        SelectionText: '<?php echo '--'.$Language->translate("Service").'--'?>',
                        Level: 1,
                        Type: 'name',
                        Type2: 'id'
                    });
                }
            })
        }



        $('#ViewReports').on('click', function (e) {

            if ($("#Analytics-Form").validationEngine('validate') == true) {

                var FilterValues = {
                    DateFrom: $('#DateFrom').val(),
                    DateTo: $('#DateTo').val(),
                    Country: GetCountryList('Name'),
                    Operator: GetOperatorList('Name'),
                    Service: GetServiceList('Name')
                };

                $('#Analytics-Report-List').trigger('refreshGrid', {filterData: FilterValues});

            }
            ;
        })


        $('#Analytics-Form').on('submit', function (e) {

            e.preventDefault
            if ($("#Analytics-Form").validationEngine('validate') == true) {
                var CountryExport = GetCountryList('Name');
                var OperatorExport = GetOperatorList('Name');
                var ServiceExport = GetServiceList('Name');
                $('#CountryExport').val(CountryExport)
                $('#OperatorExport').val(OperatorExport)
                $('#ServiceExport').val(ServiceExport)
                $('#Analytics-Form').submit();

            }

        })



    </script>
<?php include_once SHARED_VIEW . "footer.php"; ?>