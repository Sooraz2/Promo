<table id="broadcasting-calendar-details" class="table table-bordered table-sorted">
    <thead>
    <tr>
        <th style="width: 50px" class="sorting-false"><a class="table-header" field-name="TeaserType"></a></th>
        <th style="width: 50px"><a class="table-header" field-name="id"><?php echo  $Language->translate("ID") ?></a></th>
        <th style="width: 50px" class="sorting-false"><a class="table-header" field-name="is_high_priority"
                                   title="<?php echo  $Language->translate("Priority of Teaser") ?>"><img class="languageIcon"
                                                                                               style="display: inline-block"
                                                                                               src="<?php echo  BASE_URL ?>includes/teaser/priority.png"/></a>
        </th>
        <th class="sorting-false"><a class="table-header" field-name="text"><?php echo  $Language->translate("Text PO") ?></a>
        </th>

        <th class="sorting-false" width="110"><a class="table-header" field-name="service_name"><?php echo $Language->translate("Service") ?></a></th>

        <?php if( $Config->TeaserManagementColumns->Criteria==1 ):?>
            <th class="sorting-false"><a class="table-header merged-header"
                                         field-name="LanguageCriteria"><?php echo  $Language->translate("Criteria") ?></a></th>

            <?php if( $Config->AllocationCriteria->TimeCriteria==1 ):?>
                <th style="display: none" class="sorting-false"><a class="table-header merged-header"
                                                                   field-name="TimeCriteria"><?php echo  $Language->translate("Criteria") ?></a>
                </th>
            <?php endif;?>
            <?php if( $Config->AllocationCriteria->ValidTill==1 ):?>
                <th style="display: none"><a class="table-header merged-header"
                                             field-name="ValidTill"><?php echo  $Language->translate("Criteria") ?></a></th>
            <?php endif;?>
            <?php if( $Config->AllocationCriteria->SubscriberBalance==1 ):?>
                <th style="display: none"><a class="table-header merged-header"
                                             field-name="SubscriberBalance"><?php echo  $Language->translate("Criteria") ?></a>
                </th>
            <?php endif;?>
            <?php if( $Config->AllocationCriteria->TariffPlan==1 ):?>
                <th style="display: none"><a class="table-header merged-header"
                                             field-name="TariffPlan"><?php echo  $Language->translate("Criteria") ?></a></th>
            <?php endif;?>
            <th style="display: none"><a class="table-header merged-header"
                                         field-name="Termless"><?php echo  $Language->translate("Criteria") ?></a></th>

            <?php if( $Config->AllocationCriteria->BonousesBalance==1 ):?>
                <th style="display: none"><a class="table-header merged-header"
                                             field-name="Bonus"><?php echo  $Language->translate("Criteria") ?></a></th>
            <?php endif;?>
            <?php if( $Config->AllocationCriteria->LastCall==1 ):?>
                <th style="display: none"><a class="table-header merged-header"
                                             field-name="Payment"><?php echo  $Language->translate("Criteria") ?></a></th>
            <?php endif;?>
            <?php if( $Config->AllocationCriteria->LastRefil==1 ):?>
                <th style="display: none"><a class="table-header merged-header"
                                             field-name="Refill"><?php echo  $Language->translate("Criteria") ?></a></th>
            <?php endif;?>
            <?php if( $Config->AllocationCriteria->SubscriberLists==1 ):?>
                <th style="display: none"><a class="table-header merged-header"
                                             field-name="SubscriberList"><?php echo  $Language->translate("Criteria") ?></a></th>
            <?php endif;?>
            <?php if( $Config->AllocationCriteria->MsisdnPrefix==1 ):?>
                <th style="display: none"><a class="table-header merged-header"
                                             field-name="MsisdnPrefix"><?php echo  $Language->translate("Criteria") ?></a></th>
            <?php endif;?>
            <?php  if( $Config->AllocationCriteria->ActiveServices==1): ?>
                <th style="display: none"><a class="table-header merged-header"
                                             field-name="ActiveServices"><?php echo $Language->translate("Criteria") ; ?></a></th>
            <?php endif; ?>
            <?php if ($Config->AllocationCriteria->Roaming == 1): ?>
                <th style="display: none"><a class="table-header merged-header"
                                             field-name="RoamingServices"><?php echo $Language->translate("Criteria"); ?></a>
                </th>
            <?php endif; ?>
        <?php endif;?>

        <th style=""><a class="table-header" field-name="stamp">
                <?php echo  $Language->translate("Creation Date") ?>
            </a></th>
        <th class="Action"><?php echo  $Language->translate("Action") ?></th>
    </tr>
    </thead>
    <tbody></tbody>
</table>
<div id="loading-msg-activeteasers" class="loading-image"></div>
<div class="clearfix"></div>

<?php if( date($Date)>=date($CurrentDate) ):?>

    <button type="button" class="btn btn-rounded btn-success pull-right" onclick='openTeaserEditPage()'>
        <small class="glyphicon glyphicon-plus-sign"></small>
        <?php echo  $Language->translate("Add New Teaser") ?>
    </button>

    <script>
        function openTeaserEditPage(){
            var form = $("<form method='post' action='<?php echo BASE_URL; ?>TeaserManagement/TeaserEdit' />");
            form.append($('<input type="hidden" name="chars" />'));
            form.append($('<input type="hidden" name="text" />'));
            form.append($('<input type="hidden" name="referring-page" value="BroadcastingCalendar" />'));
            form.css({"display" : "none"});
            $("body").append(form);
            form.trigger("submit");
        }
    </script>

<?php endif;?>


<script type="text/javascript">

    var selectedPriority ='<?php echo $PriorityList ?>';

    var $list_item = '';

    for (var $i = 1; $i <= 100; $i++) {

        $list_item += '<li><a href="' + $i + '">' + $i + '</a></li>';
    }

    $list_item += '<li><a href="0">Remove Priority</a></li>';

    currentDate = new Date("<?php echo  $CurrentDate ?>");
    date = new Date("<?php echo  $Date ?>");
    disabled = currentDate.YmdFormat() ==  date.YmdFormat() ? "":"disabled";
    $(function () {
        $('#broadcasting-calendar-details').ajaxGrid({
            pageSize: 10000,
            defaultSortExpression: 'id',
            defaultSortOrder: 'DESC',
            tableHeading: '.table-header',
            url: '<?php echo  BASE_URL ?>BroadcastingCalendar/GetBroadCastingDetailsList',
            requestType: 'get',
            loadingImage: $('#loading-msg-activeteasers'),
            dataRowHeaderClass: [
                {noCondition: true, "class": 'table-header-text'}
            ],
            contentAdditionalProperty: [
                {
                    control : $("<div class='teaser_text' />"),
                    action : function(data, control){
                        if(data["chars"] == "1")
                            control.html(data.text).css("direction", "ltr");
                        else {
                            control.html(data.text).css("direction", "rtl");
                            //control.html(data.text).css("text-align", "right");
                        }

                        if(data["historical_data"] == 1){
                            control.attr("historical", "historical");
                        }
                    },
                    name : 'text',
                    properties : []
                }
            ],
            filterData : {
                "teaserID" : '<?php echo $teaserID ?>',
                date: '<?php echo  $Date ?>',
                "serviceName" : '<?php echo $ServiceName ?>'
            },
            postContent: [
            {
                control: $('<form style="display: inline-block" action="<?php echo  BASE_URL?>TeaserHistory/Index" method="GET"  >' +
                        '<input type="hidden" name="TeaserID" id="ID" />' +
                        "<input type='hidden' name='RedirectLink' value='BroadcastingCalendar' />" +
                        '</form>'),
                properties: [
                    {
                        propertyField: 'input[type=hidden]#ID',
                        property: 'value',
                        propertyValue: 'id'
                    }
                ],
                additionalControl : [
                    {
                        control : $("<button type='submit' title='<?php echo  $Language->translate("Views History") ?>' class='btn history_button' ><small class='fa fa-history'></small></button>"),
                        displayedWhen: {header: 'has_history', value: '1', relation: 'equal'}
                    },
                    {
                        control : $("<button type='submit' title='<?php echo  $Language->translate("Views History") ?>" +"' class='btn history_button' disabled ><small class='fa fa-history'></small></button>"),
                        displayedWhen: {header: 'has_history', value: '0', relation: 'equal'}
                    }
                ]
            },
            {
                    control: $("<form style='display: inline-block' action='' method='POST'>" +
                            "<input type='hidden' name='id' id='id' /> <input type='hidden' name='BroadcastingCalendar' value='true'>" +
                            "</form>"),
                    properties: [
                        {
                            propertyField: 'input[type=hidden]#id',
                            property: 'value',
                            propertyValue: 'id'
                        }
                    ],
                    additionalControl: [
                        {
                            control: $("<button class='btn' type='submit' name='Continue' value='Start' title='Start' "+disabled+" onclick='return Confirmation(this,\"<?php echo $Language->translate("Start Service")?>\",\"<?php echo $Language->translate("Are you sure you want to <b>Start</b> Service?")?>\", \"<?php echo $Language->translate("Yes")?>\", \"<?php echo $Language->translate("No")?>\")'><i class='glyphicon glyphicon-play'></i></button>"),
                            displayedWhen: {header: 'is_active', value: '0', relation: 'equal'},
                            formAction: '<?php echo  BASE_URL ?>TeaserManagement/DefaultTeaserActivationDeActivation'
                        },
                        {
                            control: $("<button class='btn' type='submit' name='Continue' "+disabled+" value='<?php echo  $Language->translate("Pause") ?>' title='<?php echo  $Language->translate("Pause") ?>' onclick='return Confirmation(this,\"<?php echo $Language->translate("Pause Service")?>\",\"<?php echo $Language->translate("Are you sure you want to <b>Pause</b> Service?")?>\", \"<?php echo $Language->translate("Yes")?>\", \"<?php echo $Language->translate("No")?>\")'><i class='glyphicon glyphicon-pause'></i></button>"),
                            displayedWhen: {header: 'is_active', value: '1', relation: 'equal'},
                            formAction: '<?php echo  BASE_URL ?>ActiveTeasers/Stop'
                        }
                    ]

                },

                {
                    control: $('<form style="display: inline-block" action="<?php echo  BASE_URL?>GeneralStatistics" method="POST"  >' +
                      '<button class="btn" title="<?php echo  $Language->translate("View Statistics") ?>" onclick = \'return IsModifiedDialog(this)\' ><span class="glyphicon glyphicon-stats"></span></button>' +
                            '<input type="hidden" name="teaserID" id="ID" />' +
                            '<input type="hidden" name="is_modified" id="IsModified"/>' +
                             '<input type="hidden" id="LatestSummary" name="condition" value="latest">'+
                            '</form>'),
                    properties: [
                        {
                            propertyField: 'input[type=hidden]#ID',
                            property: 'value',
                            propertyValue: 'id'
                        },
                        {
                            propertyField: 'input[type="hidden"]#IsModified',
                            property: 'value',
                            propertyValue: 'is_modified'
                        }
                    ]
                },
                {
                    control: $("<form style='display: inline-block' action='<?php echo  BASE_URL?>TeaserManagement/TeaserEdit' method='GET'>" +
                            "<input type='hidden' name='TeaserID' id='ID' />" +
                            "<input type='hidden' name='referring-page' value='BroadcastingCalendar' />" +
                            "<input type='hidden' name='PageIndex' class='TeaserCurrentPage'/> " +
                            '<button type="submit" title="<?php echo  $Language->translate("Edit") ?>" class="btn btn-rounded btn-info teaser_edit_button" >' +
                            '<small class="glyphicon glyphicon-pencil"></small>' +
                            '</button></form>'),
                    properties: [
                        {
                            propertyField: 'input[type=hidden]#ID',
                            property: 'value',
                            propertyValue: 'id'
                        }
                    ]

                }
            ],
            combineColumns: true,
            tableHeaderProperties: [
                {
                    tableHeaderClass: 'Action',
                    widthValue: 4 * 49 + 'px',
                    minWidthValue: 4 * 49 + 'px'
                },
                {
                    fieldName: 'LanguageCriteria',
                    widthValue: '167px'
                }

            ],
            id: 'id',
            NoRecordsFound: '<?php echo $Language->translate("No Records Found")?>',
            Previous: '<?php echo $Language->translate("Previous")?>',
            Next: '<?php echo $Language->translate("Next")?>',
            afterAjaxCallComplete: function () {
                afterTableLoad();
            }
        });

    });
    var isActivated = false;

    function afterTableLoad() {

        if($("body").find(".teaser_text").length){
            $("body").find(".teaser_text").each(function(){
                var attr = $(this).attr('historical');

                if(attr == "historical"){
                    var tr = $(this).closest("tr");

                    tr.find(".teaser_edit_button").attr("disabled", "disabled");
                    tr.find(".btn[name=Continue]").attr("disabled", "disabled");
                    tr.attr("class", "history_row");
                }

            });
        }

        bindOnHoverToolTip();

        $('.termless-form').next('span').hide();

        $("body").find("table tbody tr td .no-teasers-row").each(function () {
            $(this).parents("tr").find("td:last-child").html("");
            $(this).parents("tr").find("td:nth-last-child(3)").html("");
        });

        $(".planned_time_frames").on('focus', function () {

            var popOverType = "Calender";

            $termLessStatus = $(this).closest('td').find('.is_termless').val();
            $button = $(this);
            if ($termLessStatus == 1) {

                popOverType = "Termless";

                $button.attr({
                    "data-toggle": "popover",
                    "title": "<?php echo  $Language->translate("Broadcasting Dates") ?>",
                    "data-trigger": "focus",
                    "data-content": "<strong><?php echo  $Language->translate("Termless Broadcasting") ?></strong>",
                    "data-html": "true"
                });
                setpopOver($button, popOverType);


                return;
            }

            $.ajax({
                url: "<?php echo  BASE_URL ?>TeaserManagement/GetPlannedTimeFrames",
                dataType: "json",
                type: "post",
                data: {
                    messageId: $button.closest("tr").attr("id")
                },
                beforeSend: function () {
                    $html = '<div class="planned_time_frame_datepicker_container" >' +
                            '<div class="planned_time_frame_datepicker" style="background-color: #FFFFFF"></div>' +
                            '</div>'
                    $button.attr({
                        "data-toggle": "popover",
                        "title": "<?php echo  $Language->translate("Broadcasting Dates") ?>",
                        "data-trigger": "focus",
                        "data-content": $html,
                        "data-html": "true"
                    });
                    setpopOver($button, popOverType);
                },
                complete: function (data) {

                    $('#responseText').html("");
                    $('#responseText').html(data.responseText);

                    setSelected(data.responseText)

                }
            });

        });


        $("body").find(".priorityForm").each(function () {
            $(this).find("input[type='hidden']").val($(this).parents("tr").attr("id"));
        });

        $("body").find(".priorityForm ul li a").click(function (e) {
            e.preventDefault();

            var newPriority = $(this).attr("href");
            $(this).parents(".priorityForm").find(".new_value").val(newPriority);
            $(this).parents(".priorityForm").submit();
        });

        var currentActivePage = $(".pagination li.active a").attr("data-p");
        $("input[type=hidden].TeaserCurrentPage").val(currentActivePage);

        if (!isActivated) {
            ActivateCurrentActivePageIndex();
        }
    }

    function ActivateCurrentActivePageIndex() {
        <?php if(isset($PageIndex)){ ?>
        pageIndex = <?php echo $PageIndex; ?>
        <?php } ?>

        if (pageIndex != 1) {
            $(".pagination").find('a[data-p="' + pageIndex + '"]').trigger('click');
        }

        isActivated = true;
    }

    function setpopOver($button, Type) {
        var popoverscroll = "";
        if (Type == "Termless") {

            $style = 'style="width:233px; height:90px"';

        } else if (Type == "Calender") {
            $style = 'style="width:233px; height:310px"';
        } else {
            $style = 'style="min-width:130px; max-height:430px"';
            popoverscroll = "max-height: 425px;overflow-y:scroll";
        }


        $button.popover({
            placement: 'left',
            template: '<div class="popover"' + $style + '><div class="arrow" ></div><div class="popover-inner" style="' + popoverscroll + '"><h3 class="popover-title"></h3><div class="popover-content" >' +
            '</div></div></div>'
        }).popover("show");

        if (Type) {

            $('.planned_time_frame_datepicker').datetimepickerBootstrapCustom({
                inline: true,
                useCurrent: false,
                globalDateArrayName: "myDates"
            });


            $(".planned_time_frame_datepicker").on("dp.change", function (e) {

                var responseText = $('#responseText').html();

                setSelected(responseText);
            });


            $(".planned_time_frame_datepicker").on("dp.update", function (e) {

                var responseText = $('#responseText').html();

                setSelected(responseText);

            });
        }
    }

    function setSelected(jsonData) {


        var dataObj = $.parseJSON(jsonData);


        $(".day").removeClass("selected").removeClass("active");

        $.each(dataObj, function (key, value) {
            var date = moment(value.start).format('MM/DD/YYYY');
            var fotmatDate = moment(value.start).format('YYYY-MM-DD');


            $(document.body).find(".table-condensed  td.day[data-day='" + date + "']").addClass("selected");


        });
        $('.planned_time_frame_datepicker .table-condensed td.day').click(function () {

            return false;

        });

    }

</script>

<div class="dis-none" id="TimeCriteria"></div>
<div class="dis-none" id="SubBalance"></div>
<div class="dis-none" id="ValidTill"></div>
<div class="dis-none" id="SubRegion"></div>
<div class="dis-none" id="BonusesBalance"></div>
<div class="dis-none" id="LastRecharge"></div>
<div class="dis-none" id="PaidActions"></div>
<div class="dis-none" id="SubList"></div>
<div class="dis-none" id="MsisdnPrefix"></div>
<div class="dis-none" id="TariffPlan"></div>
<div class="dis-none" id="RoamingCriteria"></div>
<div class="dis-none" id="USSDShortNumbers"></div>
<div class="dis-none" id="OptionActivationCheck"></div>
<div class="dis-none" id="PlannedTimeFrames"></div>
<div class="dis-none" id="responseText"></div>
<div class="dis-none" id="SubscriberClub"></div>
<div class="dis-none" id="ActiveServices"></div>
<div class="dis-none" id="Roaming"></div>