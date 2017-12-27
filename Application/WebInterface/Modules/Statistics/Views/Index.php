<?php
/**
 *
 * @var $Language \Language\English\English
 */

$baseUrl = BASE_URL;

$Breadcrumb = "<li><a href='{$baseUrl}'>{$Language->translate('Home')}</a></li>
<li class='active'>{$Language->translate('Statistics')}</li>";

$PageTitle = $Language->translate('Statistics');

$PageLeftHeader = "";

include_once SHARED_VIEW . "base.php";

?>
    <div class="maincontent">
        <div class="contentinner">
            <div class="row-fluid">
                <div class="clearfix"></div>

                <div class="content-wrapper">

                    <div class="" style="margin-bottom: 20px">
                        <form class="form-inline form-inline-ie" method="post" id="StatisticsForm">

                            <div class="form-group">
                                <div class="input-group" style="width:300px; margin-bottom: 5px">
                                    <span class="input-group-addon btn" id="reportrange" style="padding:5px 5px"><i
                                            class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                    <input name="DateRangeStat" type="text" class="form-control" id="DateRangeStat">
                                </div>
                            </div>

                            <div class="form-group" style="margin-left: 15px; ">
                                <select class="form-control" name="Country" id="CountryList"
                                        style="width:200px; margin: 0;">
                                    <option value="">Select Country</option>
                                    <?php foreach ($CountryList as $country) {
                                        echo '<option data-value = "' . $country['name'] . '" value="' . $country['id'] . '">' . $country['name'] . '</option>';
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
                            <div class="form-group" style="margin-left: 15px">
                                <button type="submit" id="ApplyAllButton" class="btn btn-success">Apply</button>
                            </div>
                        </form>
                    </div>


                    <?php

                    $i = 2;

                    foreach ($CountryAndOperatorList as $CountryAndOperator) { ?>

                        <div style="margin-left: -15px; margin-top: 10px" class="all-operator-chart <?php echo $CountryAndOperator['Country']; ?> <?php echo $CountryAndOperator['Operator'] ?>"
                             id="<?php echo $CountryAndOperator['Operator'] . $CountryAndOperator['Country']; ?>">
                            <div class="col-sm-4">
                                <div> <?php echo $CountryAndOperator['Operator'] ?>
                                    &nbsp;&nbsp;<?php echo $CountryAndOperator['Country'] ?></div>
                                <table style="width: 90%" class="table table-bordered" id="Operator<?php echo $i ?>-table">
                                    <thead>
                                    <tr>
                                        <th rowspan="2"><a class="table-header" field-name="Service">Service</a></th>
                                        <td colspan="4" align="center"><a>Subscriptions</a></td>
                                    </tr>
                                    <tr>

                                        <th><a class="table-header" field-name="Activations">Inflow</a></th>
                                        <th><a class="table-header" field-name="Outflow1">Outflow</a></th>
                                        <th><a class="table-header" field-name="Difference1">Difference</a></th>
                                        <th><a class="table-header" field-name="Average1">The Average Outflow</a></th>
                                    </tr>
                                    </thead>

                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="col-sm-8 chart-div operator-chart<?php echo $i ?>">
                                <div class="row" id="line-chart-legend-operator<?php echo $i ?>">
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 overflow:hidden">
                                        <div id="line-chart-operator<?php echo $i ?>"
                                             style="height: 200px;  padding: 0px; position: relative;"
                                             class="line-chart-operator">
                                        </div>
                                    </div>
                                </div>

                                <div id="<?php echo $CountryAndOperator['Operator'] . "-loader" ?>"
                                     class="loading-image"></div>
                                <div class="tempTable">

                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="pagetitle"></div>
                        </div>


                        <?php $i++;
                    } ?>


                </div>
            </div>
        </div>
    </div>


    <div class="promotion-table" style="display: none">
        <table class="table table-bordered" id="promotion-table">
            <thead>
            <tr>
                <th><a class="table-header" field-name="PromotionIcon"></a></th>
                <th><a class="table-header" field-name="Promotion">Type of Promotion</a></th>
                <th><a class="table-header" field-name="Date">Date</a></th>
                <th><a class="table-header" field-name="DayName">Day</a></th>
                <th><a class="table-header" field-name="PromotionText">Text</a></th>
                <th><a class="table-header" field-name="Views">Quantity of</a></th>
                <th><a class="table-header" field-name="Activation1">Activations</a></th>
                <th><a class="table-header" field-name="ActivationPercent">Activations % </a></th>
            </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>


    <script type="text/javascript">

        var OperatorList = '<?php echo json_encode($Operator); ?>'
        var CountryAndOperatorList = '<?php echo json_encode($CountryAndOperatorList); ?>'
        var CountryAndOperatorList = JSON.parse(CountryAndOperatorList);
        var OperatorList = JSON.parse(OperatorList);

        var CountryAndOperatorAndService = JSON.parse('<?php echo json_encode($CountryAndOperatorAndService); ?>');

        var selectedDate = ''
        $(function () {

            $(".leftmenu").find(".active").removeClass("active");
            $('#menu-Statistics').addClass('active');

            $('#DateRangeStat').datetimepickerBootstrap({
                viewMode: 'months',
                format: 'MMMM YYYY',
                defaultDate: new Date()
            })

            var filterValues = '';


            var i = 2


            $.each(CountryAndOperatorList, function (data, value) {

                filterValues = {
                    Operator: value.Operator,
                    Country: value.Country,
                    Service:GetServiceListByCountryAndOperator(value.Country, value.Operator)

                }

                $('#Operator' + i + '-table').ajaxGrid({
                    pageSize: 5,
                    defaultSortExpression: 'Service',
                    defaultSortOrder: 'ASC',
                    tableHeading: '.table-header',
                    url: '<?php echo  BASE_URL ?>Statistics/ListAllOperator2',
                    requestType: 'get',
                    filterData: {filterData: filterValues},
                    loadingImage: $('#' + value.name + '-loader'),
                    id: 'id',
                    NoRecordsFound: '<?php echo $Language->translate("No Records Found")?>',
                    Previous: '<?php echo $Language->translate("Previous")?>',
                    Next: '<?php echo $Language->translate("Next")?>'
                });

                i++;
            })


        });


        MainChartLoad({Date: '', Country: '', Operator: '', Operator: '', Service: ''})

        function MainChartLoad(jsonPostData) {
            i = 2;

            $.each(CountryAndOperatorList, function (data, value) {

                var ServiceDropDown = jsonPostData.Service;
                var OperatorDropDown = jsonPostData.Operator = value.Operator
                var CountryDropDown = jsonPostData.Country = value.Country


                var viewsColor = ["#2FABE9"];

                var CountChart = i;

                BuildLineChart($("#line-chart-operator" + i),
                    "<?php echo  BASE_URL ?>Statistics/StatChartViews",
                    {'filterData': jsonPostData},
                    {
                        'chartTitle': "Monthly Statistics",
                        'ReportType': "Monthly",
                        'dateRange': "1-30",
                        'toolTipNewDesign': 1,
                        'xAxisColumn': {
                            Field: 'CalendarDate',
                            // DisplayColumn: 'hour',
                            DataType: 'DateTime',
                            'DateTimeFormat': "%d/%m/%Y"
                        },
                        'lineHeaderColumns': [
                            {
                                Field: 'ActivationNew',
                                DisplayLabel: '<?php echo  $Language->translate("Activations") ?>',
                                DataType: 'Int'
                            }
                        ],
                        'lineColorValues': viewsColor,
                        'showLegend': true,
                        'legendDivElement': $("#line-chart-legend-operator" + i)
                    }, {
                        CallBack: function () {

                                  var options = '';

                                  var Service  = GetServiceArrayByCountryAndOperator(CountryDropDown, OperatorDropDown);

                                    $('#line-chart-legend-operator' + CountChart + '-service').remove();


                                    options += '<select data-service= "' + ServiceDropDown + '" data-country= "' + CountryDropDown + '" data-operator= "' + OperatorDropDown + '" data-index="' + CountChart + '"  id="line-chart-legend-operator' + CountChart + '-service" style="width: 16%; margin-left: 1%; margin-top: -2%" class="form-control service-selector"><option value="">Select Service</option>'

                                    Service.forEach(function (dataService) {

                                        options += '<option value="' + dataService + '">' + dataService + '</option>'

                                    })
                                    options += '</select>'
                                    $("#line-chart-legend-operator" + CountChart).next().prepend(options);


                        }
                    });

                i++

            })
        }

        function LoadStatisticsChart2(jsonPostData= '', i) {

            var viewsColor = ["#2FABE9"];
            var Count = i;

           // var ServiceDropDown = jsonPostData.Service;

           // if (ServiceDropDown == '') {

              //  ServiceDropDown = jsonPostData.Country = GetServiceListByCountryAndOperator(jsonPostData.Country, jsonPostData.Operator);
          //  }



            BuildLineChart($("#line-chart-operator" + i),
                "<?php echo  BASE_URL ?>Statistics/StatChartViews",
                {'filterData': jsonPostData},
                {
                    'chartTitle': "Monthly Statistics",
                    'ReportType': "Monthly",
                    'dateRange': "1-30",
                    'toolTipNewDesign': 1,
                    'xAxisColumn': {
                        Field: 'CalendarDate',
                        // DisplayColumn: 'hour',
                        DataType: 'DateTime',
                        'DateTimeFormat': "%d/%m/%Y"
                    },
                    'lineHeaderColumns': [
                        {
                            Field: 'ActivationNew',
                            DisplayLabel: '<?php echo  $Language->translate("Activations") ?>',
                            DataType: 'Int'
                        }
                    ],
                    'lineColorValues': viewsColor,
                    'showLegend': true,
                    'legendDivElement': $("#line-chart-legend-operator" + i)
                }, {
                    CallBack: function () {


                        var options = '';


                        var Service  = GetServiceArrayByCountryAndOperator(jsonPostData.Country, jsonPostData.Operator);

                        $('#line-chart-legend-operator' + Count + '-service').remove();

                        options += '<select data-service= "' + jsonPostData.Service + '"  data-country= "' + jsonPostData.Country + '" data-operator= "' + jsonPostData.Operator + '" data-index="' + Count + '"  id="line-chart-legend-operator' + Count + '-service" style="width: 16%; margin-left: 1%; margin-top: -2%" class="form-control service-selector"><option value="">Select Service</option>'
                        Service.forEach(function (dataService) {

                            options += '<option value="' + dataService + '">' + dataService + '</option>'

                        })
                        options += '</select>'
                        $("#line-chart-legend-operator" + Count).next().prepend(options);

                        $('#line-chart-legend-operator' + i + '-service option[value="' + jsonPostData.SelectedService + '"]').prop('selected', true)

                    }
                });

        }


        $("body").on('change', '.service-selector', function () {

            var ServiceValue = ''
            if ($(this).val() != '') {
                ServiceValue = "'" + $(this).val() + "'";
            } else {
                ServiceValue = GetServiceListByCountryAndOperator($(this).attr('data-country'),$(this).attr('data-operator'))
            }
            var FilterValues = {
                Date: selectedDate,
                Country: $(this).attr('data-country'),
                Operator: $(this).attr('data-operator'),
                Service: ServiceValue,
                SelectedService: $(this).val()
            };

            $('.promotion-table').hide();

            $("#line-chart-operator" + $(this).attr('data-index')).html('');
            LoadStatisticsChart2(FilterValues, $(this).attr('data-index'));
        })


        $('#StatisticsForm').on('submit', function (e) {


            ShowHideOperatorChart();


            e.preventDefault();

            $('.promotion-table').hide()

            var formvalue = $(this).serializeArray();

            var date = moment(formvalue[0]['value']).format("YYYY-MM-DD");

            selectedDate = date;

            var FilterValues = {
                Date: date,
                Country: '',
                Operator: '',
                Service: GetServiceList('Name')
            };


            $(".line-chart-operator").html('');

            MainChartLoad(FilterValues);

            i = 2;

            $.each(CountryAndOperatorList, function (data, value) {

                FilterValues.Operator = value.Operator;
                FilterValues.Country = value.Country;
                FilterValues.Service = GetServiceListByCountryAndOperator(value.Country,value.Operator);

                $('#Operator' + i + '-table').trigger('refreshGrid', {filterData: FilterValues});

                i++;
            })
        })


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


        function ShowHideOperatorChart() {

            if ($('#CountryList').next().find('.multi-select-child-ul .multi-select-child-li input').hasClass('multiselect-Selected')) {

                $('.all-operator-chart').hide();

                $('#CountryList').next().find('.multi-select-child-ul .multi-select-child-li').each(function (data) {

                    if ($(this).find('input').hasClass('multiselect-Selected')) {

                        var CountryID = $(this).find('span').text();

                        $('.' + CountryID).show();


                        if ($('#OperatorList').next().find('.multi-select-child-ul .multi-select-child-li input').hasClass('multiselect-Selected')) {

                            $('.' + CountryID).hide();

                            $('#OperatorList').next().find('.multi-select-child-ul .multi-select-child-li').each(function (data) {

                                if ($(this).find('input').hasClass('multiselect-Selected')) {

                                    var OperatorID = $(this).find('span').text();

                                    if($('.' + OperatorID).hasClass(CountryID)) {
                                        //$('.' + OperatorID).show();
                                        debugger;

                                        $('#' + OperatorID+CountryID).show();
                                    }
                                }
                            })
                        }

                    }
                })
            }
            else {

                $('.all-operator-chart').show();
            }

        }


        var CountryList = <?php echo $CountryListAll; ?>;
        var OperatorListAll = '';
        var ServiceList = '';


        $('#CountryList').multiSelect({
            data: CountryList,
            SelectionText: '<?php echo '--'.$Language->translate("Country").'--'?>',
            Level: 1,
            Type: 'name',
            Type2: 'id'

        });

        $('#OperatorList').multiSelect({
            data: OperatorListAll,
            SelectionText: '<?php echo '--'.$Language->translate("Operator").'--'?>',
            Level: 1,
            Type: 'name',
            Type2: 'id'

        });

        $('#ServiceList').multiSelect({
            data: ServiceList,
            SelectionText: '<?php echo '--'.$Language->translate("Service").'--'?>',
            Level: 1,
            Type: 'name',
            Type2: 'id'

        });


        $('#CountryList').next('.multi-select-container').find('.multi-select-section').on('mouseleave', function () {

            var CountryListID = GetCountryList('ID');

            if (CountryListID.length > 0) {
                $.ajax({
                    url: "<?php echo BASE_URL ?>Analytics/GetOperatorByCountry",
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
                        url: "<?php echo BASE_URL ?>Analytics/GetServiceByOperator",
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

        function showPromotionTable(thisObj, x, total) {
            var ServiceValue = ''

            if ($(thisObj).parent().prev('select').val() != '') {

                ServiceValue = "'" + $(thisObj).parent().prev('select').val() + "'";
            }
            if (ServiceValue == '') {

                ServiceValue = $(thisObj).parent().prev('select').attr('data-service');
            }

            var filterValues = {
                Date: x,
                Country: $(thisObj).parent().prev('select').attr('data-country'),
                Operator: $(thisObj).parent().prev('select').attr('data-operator'),
                Service: ServiceValue

            };

            var Table = $('.promotion-table').clone().show();

            $('.promotion-table').remove();

            $(thisObj).parents('.chart-div').find('.tempTable').html(Table);

            $('#promotion-table').ajaxGrid({
                pageSize: 5,
                defaultSortExpression: '',
                defaultSortOrder: 'ASC',
                tableHeading: '.table-header',
                url: BASE_URL + 'Statistics/GetPromotionDetails',
                requestType: 'get',
                filterData: {filterData: filterValues},
                loadingImage: $('.views-loader'),
                id: 'id',
                NoRecordsFound: 'No Records Found',
                Previous: '"Previous',
                Next: 'Next',
                afterAjaxCallComplete: function () {


                   /* var ActivationValue = (total / $("#promotion-table tbody tr").length)

                    $("#promotion-table tbody tr").each(function () {

                        $(this).find('td').eq(6).text(Math.round(ActivationValue));

                        var view = $(this).find('td').eq(5).text();

                        if(view ==0){

                            $(this).find('td').eq(7).text('0%');
                        }else {
                           // $(this).find('td').eq(7).text(Math.round((ActivationValue * 100) / view) + '%');
                            $(this).find('td').eq(7).text( parseFloat((ActivationValue * 100) / view).toFixed(2)+ '%');

                        }

                    });*/
                }
            });


        }


        function GetServiceArrayByCountryAndOperator(Country, Operator) {
            var Service = new Array();
            $.each(CountryAndOperatorAndService, function (serviceData, servicevalue) {
                if (servicevalue[Country] != undefined && servicevalue[Country][Operator] != undefined) {
                    Service.push(servicevalue[Country][Operator]);
                }
            })
            return Service;
        }

        function GetServiceListByCountryAndOperator(Country, Operator) {
            var Service = ''
            $.each(CountryAndOperatorAndService, function (serviceData, servicevalue) {

                if (servicevalue[Country] != undefined && servicevalue[Country][Operator] != undefined) {
                    Service += "'" + servicevalue[Country][Operator] + "',";
                }
            })
            if (Service.length > 0) {
                return Service.slice(0, -1)
            }
            return Service;
        }


    </script>
<?php include_once SHARED_VIEW . "footer.php"; ?>