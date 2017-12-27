(function ($) {
    $.fn.broadcastingCalendar = function (options) {

        return this.each(function () {
            var $container = null;
            var $triggerControl = null;
            var broadCastingDetails = $('<tr class="broadcasting-details" style="display: none"><td colspan="7"></td></tr>');
            var $control = $(this);
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var russianMonths = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
            var weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            var russianWeekDays = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
            var daySelector = 'td:not(:empty)';
            var $message = "Broadcasting date:";
            var searchTeaser = "";
            var ServiceName = "";
            var CountryName = "";
            var OperatorName = "";

            var CountryDataHTML = ""
            var CountryID = ""
            var OperatorDataHTML = ""
            var OperatorID = ""
            var ServiceDataHTML = ""
            var ServiceID = ""
            var PromotionDataHTML = ""
            var PromotionID = ""
            var PromotionTextValue = ""
            var OperatorServiceList = "";
            if (options && options.Message != "") {
                $message = options.Message;
            }

            var TeaserID = "Teaser ID";

            if (options && options.TeaserID != "") {
                TeaserID = options.TeaserID;
            }



            $("body").on('click','.boardcasting-add-button', function(){

                var countryValue = $('#broadcasting-calendar-country-filter').val();
                var operatorValue = $('#broadcasting-calendar-operator-filter').val();
                var promotionValue  =  $('#broadcasting-calendar-promotion-filter').val();
                showAddNewForm("Add Promotion",BASE_URL+"BroadcastingCalendar/Form?country="+countryValue+"&operator="+operatorValue,500,145);
            })



            $("body").on("change", ".search-header select", function(){
                var date = $("body").find(".calendar-table").attr("data-current-date").replace(" ", "T");
                date = new Date(date);
                self.setHTML();
                $container.find('.calendar-section').empty().append(self.buildCalender(date));
            });


            $("body").on("click", "#search-promotion-text-button", function(){
                var date = $("body").find(".calendar-table").attr("data-current-date").replace(" ", "T");
                date = new Date(date);
                self.setHTML();
                $container.find('.calendar-section').empty().append(self.buildCalender(date));
            });

            $("body").on("click", ".search-promotion-text-clear-button", function(){
                $('.search-promotion-text-field').val('');
                var date = $("body").find(".calendar-table").attr("data-current-date").replace(" ", "T");
                date = new Date(date);
                self.setHTML();
                $container.find('.calendar-section').empty().append(self.buildCalender(date));
            });

            $("body").on("click", ".search-promotion-text-button", function(){
                var date = $("body").find(".calendar-table").attr("data-current-date").replace(" ", "T");
                date = new Date(date);
                self.setHTML();
                $container.find('.calendar-section').empty().append(self.buildCalender(date));
            });

            var self = {
                initialize: function () {
                    $container = self.initializeContainer().append(self.buildCalender(new Date()))
                        .on('click', '.previous-link', self.loadPrevious)
                        .on('click', '.todaySelect', self.loadToday)
                        .on('click', 'td.drop-event', self.showHideDetails)
                        .on('click', '.next-link', self.loadNext)
                        .on('mouseenter', 'td.drop-event', self.showDropper)
                        .on('mouseleave', 'td.drop-event', self.hideDropper)
                        .on('mouseenter', '.event-placeholder .teaser-icon', self.showHoverId)
                        .on('mouseleave', '.event-placeholder .teaser-icon', self.hideHoverId)
                        .on('mouseenter', '.event-placeholder .teaser-icon', self.highlightSameTeaser)
                        .on('mouseleave', '.event-placeholder .teaser-icon', self.removeHighlightSameTeaser)
                        .on('mouseenter mouseleave', '.prev', self.addPrevHoverClass)
                        .on('mouseenter mouseleave', '.next', self.addNextHoverClass);

                    //.on('change', '.monthSelect', self.pickDate)
                    //    .on('change', '.yearSelect', self.pickDate)

                    $control.html($container);
                },

                showHoverId: function () {
                    var isLegend = $(this).closest(".legend-section").length > 0;
                    if (!isLegend) {
                        var hoverTeaserId = $('<i class="teaser-id-hover"></i>');
                        var text = $('<i class=""></i>');
                        var arrowDown = $('<i class="glyphicon glyphicon-arrow-down"></i>');

                        text.text(decodeURIComponent($(this).attr('title-data-text')));
                        hoverTeaserId.append(text);
                        $(this).append(hoverTeaserId);
                        $(this).append(arrowDown);

                        if( text.height() > hoverTeaserId.height() ){
                            text.text(text.text().substr(0, 80)+"...");
                        }
                    }
                }, hideHoverId: function () {
                    $(this).html('');
                },
                highlightSameTeaser: function () {
                    var teaserType = $(this).attr('type');
                    var teaserId = $(this).attr('id');

                    var teaserClass = self.getTeaserClass(teaserType);

                    $container.find('table td').removeClass('default-teaser-icon-light');
                    $container.find('table td').removeClass('termless-teaser-icon-light');
                    $container.find('table td').removeClass('priority-teaser-icon-light');
                    $container.find('table td').removeClass('other-teaser-icon-light');

                    $container.find('table td .teaser-icon[id="' + teaserId + '"]').closest('td').addClass(teaserClass + "-light");
                }, removeHighlightSameTeaser: function () {
                    $container.find('table td').removeClass('default-teaser-icon-light');
                    $container.find('table td').removeClass('termless-teaser-icon-light');
                    $container.find('table td').removeClass('priority-teaser-icon-light');
                    $container.find('table td').removeClass('other-teaser-icon-light');
                },
                showDropper: function () {
                    $container.find('table td:not(td.selected) > div.date-event-wrapper').find('#cal-day-tick').remove();

                    var downArrow = $('<div id="cal-day-tick""><i class="icon-chevron-down glyphicon glyphicon-chevron-down"></i></div>');

                    $(this).children(".date-event-wrapper").append(downArrow);

                },
                hideDropper: function () {
                    $container.find('table td:not(td.selected) > div.date-event-wrapper').find('#cal-day-tick').remove();
                },

                showHideDetails: function () {

                    if($('#broadcasting-calendar-country-filter').val()<1) {
                        return
                    }
                    var date = $(this).attr('date');
                    SetCookie("Balance_Plus_Chinguitel_Broadcasting_calendar_active_date", date);

                    $container.find('table td').removeClass('selected');

                    $(this).addClass('selected');

                    if ($(this).closest('tr').next('tr.broadcasting-details').length == 1 && $(this).closest('tr').next('tr.broadcasting-details').attr('date') == date) {
                        $(this).closest('tr').next('tr.broadcasting-details').remove();
                        $(this).removeClass('selected');
                        $container.find('table td:not(td.selected) > .date-event-wrapper').find('#cal-day-tick').remove();
                        return;
                    }

                    $container.find('table td:not(td.selected) > .date-event-wrapper').find('#cal-day-tick').remove();

                    var downArrow = $('<div id="cal-day-tick""><i class="icon-chevron-down glyphicon glyphicon-chevron-down"></i></div>');

                    $(this).children(".date-event-wrapper").append(downArrow);

                    var selectedDate = new Date(date);

                    var year = parseInt($container.find('.yearSelect').attr('year'));

                    var month = parseInt($container.find('.monthSelect').attr('month'));

                    var currentDate = new Date(year, month - 1, 1);

                    var firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);

                    var lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

                    if (moment(date).toDate().getMonth() != currentDate.getMonth()) {
                        if (selectedDate > lastDay) {
                            self.loadNext();
                        } else if (selectedDate < firstDay) {
                            self.loadPrevious();
                        }
                        $triggerControl = $container.find('table tr td[date="' + date + '"]');
                        return;
                    }

                    broadCastingDetails.find('td').html('');
                    broadCastingDetails.insertAfter($(this).closest('tr'));
                    broadCastingDetails.slideDown(2000);
                    $.ajax({
                        url: options.detailsUrl,
                        type: 'GET',
                        data: {Date: date,Country:$('#broadcasting-calendar-country-filter').val(),Operator:$('#broadcasting-calendar-operator-filter').val(),Service:$('#broadcasting-calendar-select-service').val(),Promotion:$('#broadcasting-calendar-promotion-filter').val()},
                        dataType: "html",
                        beforeSend: function () {

                        },
                        complete: function () {

                        },
                        success: function (data) {
                            broadCastingDetails.find('td').html(data);
                            broadCastingDetails.attr('date', date);
                        }
                    });
                },

                hide: function () {
                    $container.fadeOut(200);
                },
                buttonClick: function () {

                    self.hide();
                },
                clicked: function () {

                    $container.find('td a.ui-state-active').removeClass('ui-state-active');
                    $(this).find('a');
                },
                loadPrevious: function () {
                    var year = parseInt($container.find('.yearSelect').attr('year'));
                    var month = parseInt($container.find('.monthSelect').attr('month'));
                    if (month == 1) {
                        year = year - 1;
                        month = 12;
                    } else {
                        month = month - 1;
                    }
                    var englishDate = new Date(month + "/1/" + year);

                    self.setHTML();
                    $container.find('.calendar-section').empty().append(self.buildCalender(englishDate));
                    $container.find('td a.ui-state-active').removeClass('ui-state-active');
                },
                loadToday: function () {
                    var currentDate = new Date();

                    var englishDate = new Date((currentDate.getMonth() + 1) + "/1/" + currentDate.getFullYear());

                    self.setHTML();
                    $container.find('.calendar-section').empty().append(self.buildCalender(englishDate));
                    $container.find('td a.ui-state-active').removeClass('ui-state-active');
                },
                loadNext: function () {
                    var year = parseInt($container.find('.yearSelect').attr('year'));
                    var month = parseInt($container.find('.monthSelect').attr('month'));
                    if (month == 12) {
                        year = year + 1;
                        month = 1;
                    } else {
                        month = month + 1;
                    }
                    var englishDate = new Date(month + "/1/" + year);

                    self.setHTML();
                    $container.find('.calendar-section').empty().append(self.buildCalender(englishDate));

                },
                addPrevHoverClass: function () {
                    $(this).toggleClass('ui-state-hover ui-datepicker-prev-hover');
                },
                addNextHoverClass: function () {
                    $(this).toggleClass('ui-state-hover ui-datepicker-next-hover');
                },
                hover: function () {
                    $(this).toggleClass('ui-state-hover');
                },
                initializeContainer: function () {

                    if ($('.dpp').length == 0) {
                        $('<div class="dpp"/>').insertAfter($('body'));
                    }

                    var eventPicker = $('<div>').addClass('event-calendar-container');

                    $('.dpp').html(eventPicker);

                    return eventPicker;
                },


                _daylightSavingAdjust: function (date) {
                    if (!date) {
                        return null;
                    }
                    date.setHours(date.getHours() > 12 ? date.getHours() + 2 : 0);
                    return date;
                },

                _getFirstDayOfMonth: function (year, month) {
                    return new Date(year, month, 1).getDay();
                },

                buildLegend: function () {
                    var legendSection = $('<div class="legend-section"/>');

                    legendSection.append("<h5>" + options.GuideNotation + "</h5>");

                    var ul = $('<ul/>');

                    ul.append($('<li><span class="teaser-icon balanceplus-boardcast-icon"></span>' + options.BalancePlus + '</li>'));
                    ul.append($('<li><span class="teaser-icon ivr-boardcast-icon"></span>' + options.IVRBoardcaster + '</li>'));
                    ul.append($('<li><span class="teaser-icon icb-boardcast-icon"></span>' + options.ICBBoardcaster + '</li>'));
                    ul.append($('<li><span class="teaser-icon sms-boardcast-icon"></span>' + options.SMSBoardcast + '</li>'));
                    ul.append($('<li><span class="teaser-icon sns-boardcast-icon"></span>' + options.SNS + '</li>'));
                    ul.append($('<li><span class="teaser-icon wrongivr-boardcast-icon"></span>' + options.WrongIVR + '</li>'));
                    ul.append($('<li><span class="teaser-icon wrongstar-boardcast-icon"></span>' + options.WrongStar + '</li>'));

                    return legendSection.append(ul);

                },

                buildCalender: function (currentDate) {
                    self.buildLegend();

                    var calendarSection = $('<div class="calendar-section"/>');

                    calendarSection.append(self.buildLegend);

                    var inst = {};

                    var todayDate = new Date();

                    var firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);

                    var totalDays = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();


                    var searchHeader = $('<div class="search-header"></div>');
                    searchHeader.css({
                        "margin-bottom": "5px"
                    })
                    /* Filter Added for Unifun Interface*/

                    var countryFilter = $('<select class="form-control" id="broadcasting-calendar-country-filter" />');
                    countryFilter.css({
                        width: "150px",
                        margin: "0 0 0 5px",
                        display: "inline-block",
                        "vertical-align" : "middle"
                    });
                    countryFilter.append($('<option value="" >Select Country</option>'));
                    for(var i = 0; i < options.Data.CountryList.length; i++){
                        if(CountryName != "" && CountryName == options.Data.CountryList[i]["name"])
                            countryFilter.append($('<option value="'+options.Data.CountryList[i]["id"]+'" selected >'+options.Data.CountryList[i]["name"]+'</option>'));
                        else
                            countryFilter.append($('<option value="'+options.Data.CountryList[i]["id"]+'" >'+options.Data.CountryList[i]["name"]+'</option>'));
                    }



                  //  CountryName = $("#broadcasting-calendar-operator-filter")[0].outerHTML;

                    var operatorFilter = $('<select class="form-control" id="broadcasting-calendar-operator-filter" />');
                    operatorFilter.css({
                        width: "160px",
                        margin: "0 0 0 5px",
                        display: "inline-block",
                        "vertical-align" : "middle"
                    });
                    operatorFilter.append($('<option value="" >Select Operator</option>'));

                    for(var i = 0; i < options.Data.OperatorList.length; i++){
                        if(OperatorName != "" && ServiceName == options.Data.OperatorList[i]["name"])
                            operatorFilter.append($('<option value="'+options.Data.OperatorList[i]["id"]+'" selected >'+options.Data.OperatorList[i]["name"]+'</option>'));
                        else
                            operatorFilter.append($('<option value="'+options.Data.OperatorList[i]["id"]+'" >'+options.Data.OperatorList[i]["name"]+'</option>'));
                    }

                    var serviceFilter = $('<select class="form-control" id="broadcasting-calendar-select-service" />');
                    serviceFilter.css({
                        width: "150px",
                        margin: "0 0 0 5px",
                        display: "inline-block",
                        "vertical-align" : "middle"
                    });
                    serviceFilter.append($('<option value="" >Select Service</option>'));
                    for(var i = 0; i < options.Data.OperatorServiceList.length; i++){
                        if(ServiceName != "" && ServiceName == options.Data.OperatorServiceList[i]["service_name"])
                            serviceFilter.append($('<option value="'+options.Data.OperatorServiceList[i]["id"]+'" selected >'+options.Data.OperatorServiceList[i]["name"]+'</option>'));
                        else
                            serviceFilter.append($('<option value="'+options.Data.OperatorServiceList[i]["idbroadcasting-calendar-select-service"]+'" >'+options.Data.OperatorServiceList[i]["name"]+'</option>'));
                    }


                    var promotionFilter = $('<select class="form-control" id="broadcasting-calendar-promotion-filter" />');
                    promotionFilter.css({
                        width: "250px",
                        margin: "0 0 0 5px",
                        display: "inline-block",
                        "vertical-align" : "middle"
                    });
                    promotionFilter.append($('<option value="" >Filter By Type of Promotion</option> <option>Balance Plus</option> <option>IVR Broadcaster</option><option>ICB Broadcaster</option>'));
                    promotionFilter.append($('<option >SMS Broadcast</option> <option >SNS</option> <option>Wrong IVR</option><option>Wrong Star(*)</option>'));


                    if(OperatorDataHTML!=''){
                        countryFilter = CountryDataHTML
                        operatorFilter = OperatorDataHTML
                        serviceFilter = ServiceDataHTML
                        promotionFilter = PromotionDataHTML
                    }

                    searchHeader.append(countryFilter);
                    searchHeader.append(operatorFilter);
                    searchHeader.append(serviceFilter);
                    //calendarHeader.append(serviceFilter);
                    searchHeader.append(promotionFilter);

                    var calendarHeader = $('<div class="calendar-header"></div>');


                    var previousLink = $('<a class="previous-link"></a>');
                    var previousSpan = $('<span title="Previous Month" class="glyphicon glyphicon-arrow-left"></span>');
                    previousLink.append(previousSpan);

                    var nextLink = $('<a class="next-link"></a>');
                    var nextSpan = $('<span title="Next Month" class="glyphicon glyphicon-arrow-right"></span>');
                    nextLink.append(nextSpan);

                    var monthContainer = $('<div class="month-container"/>');

                    var todaySelect = $('<span class="todaySelect"></span>');
                    var monthSelect = $('<span class="monthSelect"></span>');
                    var addButton = $('<div ><button  type="button" disabled class="btn btn-rounded btn-success boardcasting-add-button"><small class="glyphicon glyphicon-plus-sign"></small> Add </button></div>');

                    addButton.css({
                        "padding-left": "10px",
                         display: "inline",
                        "padding-right": "10px"
                    })

                    if(OperatorID != undefined && OperatorID!=''){
                        $(addButton).find('button').prop('disabled',false);
                    }
                    todaySelect.text(options.Today);

                    switch (options.language) {
                        case "English":
                            monthSelect.text(months[currentDate.getMonth()]);
                            break;
                        default :
                            monthSelect.text(russianMonths[currentDate.getMonth()]);
                            break;
                    }

                    monthSelect.attr('month', currentDate.getMonth() + 1);

                    var yearSelect = $('<span class="yearSelect"></span>');

                    yearSelect.text(currentDate.getFullYear());

                    yearSelect.attr('year', currentDate.getFullYear());

                    monthContainer.append(previousLink);
                    monthContainer.append(monthSelect);
                    monthContainer.append(nextLink);

                    /*sajesh added*/
                    var searchTeaserID = $('<div class="input-append form-inline form-inline-ie" />');
                    searchTeaserID.css(
                        {
                            display: "inline-block",
                            "vertical-align" : "middle",
                            margin : "0 0 0 10px"
                        }
                    );
                    var promotionValue = '';
                    if(PromotionTextValue!=''){
                        promotionValue = PromotionTextValue;
                    }

                    var searchTeaserIDField = $('<input type="text" value="'+promotionValue+'" class="span8 form-control form-control-ie search-promotion-text-field" placeholder="Search by Promotion Text" style="margin:0; min-width: 200px" />');
                    var searchTeaserIDButton = $('<button type="button" class="btn search-promotion-text-button" style="margin:0" />');
                    searchTeaserIDButton.html('<span class="icon-search"></span>');
                    var searchTeaserIDClearButton = $('<button type="button" class="btn search-promotion-text-clear-button" style="margin-left: -3px" />');
                    searchTeaserIDClearButton.html('<span class="icon-remove"></span>');

                    searchTeaserID.append(searchTeaserIDField);
                    searchTeaserID.append(searchTeaserIDButton);
                    searchTeaserID.append(searchTeaserIDClearButton);
                    /**/

                   /* var serviceFilter = $('<select class="form-control" id="broadcasting-calendar-select-service" />');
                    serviceFilter.css({
                        width: "138px",
                        margin: "0 0 0 5px",
                        display: "inline-block",
                        "vertical-align" : "middle"
                    });
                    serviceFilter.append($('<option value="" >Select Service</option>'));
                    for(var i = 0; i < options.Data.ServiceOptions.length; i++){
                        if(ServiceName != "" && ServiceName == options.Data.ServiceOptions[i]["service_name"])
                            serviceFilter.append($('<option value="'+options.Data.ServiceOptions[i]["service_name"]+'" selected >'+options.Data.ServiceOptions[i]["service_name"]+'</option>'));
                        else
                            serviceFilter.append($('<option value="'+options.Data.ServiceOptions[i]["service_name"]+'" >'+options.Data.ServiceOptions[i]["service_name"]+'</option>'));
                    }
*/
                    //calendarHeader.append(todaySelect);
                    calendarHeader.append(addButton);
                    calendarHeader.append(monthContainer);
                    calendarHeader.append(searchTeaserID);
                    //calendarHeader.append(serviceFilter);
                    calendarHeader.append(yearSelect);

                    var weeks = Math.ceil((totalDays + firstDay.getDay()) / 7);

                    var $table = $("<table class='calendar-table' data-current-date='"+moment(currentDate).format("YYYY-MM-DD hh:mm:ss")+"' />");
                    var count;

                    var $thead = $('<thead/>');

                    var $trHead = $('<tr/>');
                    $thead.append($trHead);

                    $table.append($thead);

                    for (var k = 0; k < 7; k++) {
                        var $th = $('<th/>');

                        switch (options.language) {
                            case "English":
                                $th.text(weekDays[k]);
                                break;
                            default :
                                $th.text(russianWeekDays[k]);
                                break;
                        }


                        $trHead.append($th);
                    }

                    var drawYear = currentDate.getFullYear();
                    var drawMonth = currentDate.getMonth();
                    var startingDay = 1;

                    var daysInMonth = totalDays;
                    if (drawYear === inst.selectedYear && drawMonth === inst.selectedMonth) {
                        inst.selectedDay = Math.min(inst.selectedDay, daysInMonth);
                    }
                    var leadDays = (self._getFirstDayOfMonth(drawYear, drawMonth) - startingDay + 7) % 7;
                    var numRows = Math.ceil((leadDays + daysInMonth) / 7);
                    var $tbody = $('<tbody/>');

                    var printDate = self._daylightSavingAdjust(new Date(drawYear, drawMonth, 1 - leadDays));
                    for (var dRow = 0; dRow < numRows; dRow++) { // create date picker rows
                        var $tr = $('<tr/>');
                        $tbody.append($tr);

                        for (var dow = 0; dow < 7; dow++) { // create date picker days
                            var $td = $('<td/>');
                            var $div = $('<div class="date-event-wrapper"/>');
                            if (todayDate.getFullYear() == printDate.getFullYear() && todayDate.getMonth() == printDate.getMonth() && todayDate.getDate() == printDate.getDate()) {
                                $td.addClass('today');
                            }

                            $td.attr('date', printDate.getFullYear() + "-" + ("0" + (printDate.getMonth() + 1)).slice(-2) + "-" + ("0" + printDate.getDate()).slice(-2));

                            var otherMonth = (printDate.getMonth() !== drawMonth);

                            if (printDate.getDate() == 1) {

                                switch (options.language) {
                                    case "English":
                                        $div.html("<a class='date'>" + months[printDate.getMonth()] + " " + printDate.getDate() + "</a>");
                                        break;
                                    default :
                                        $div.html("<a class='date'>" + russianMonths[printDate.getMonth()] + " " + printDate.getDate() + "</a>");
                                        break;
                                }


                            } else {
                                $div.html("<a class='date'>" + printDate.getDate() + "</a>");
                            }

                            printDate.setDate(printDate.getDate() + 1);

                            printDate = this._daylightSavingAdjust(printDate);

                            $div.append("<div class='event-placeholder'/>");
                            $td.append($div);
                            $tr.append($td);
                        }
                    }

                    $table.append($tbody);

                    calendarSection.append(searchHeader);
                    calendarSection.append(calendarHeader);


                    calendarSection.append($table);

                    var firstDate = calendarSection.find('tbody tr:first-child td:first-child').attr('date');

                    var lastDate = calendarSection.find('tbody tr:last-child td:last-child').attr('date');

                    self.setValue(firstDate, lastDate);

                    return calendarSection;
                }, getTeaserClass: function (teaserType) {

                    var teaserClass = "";


                    switch (teaserType) {
                        case 'Balance Plus':
                            teaserClass = 'balanceplus-boardcast-icon';
                            break;
                        case 'IVR Broadcaster':
                            teaserClass = 'ivr-boardcast-icon';
                            break;
                        case 'ICB Broadcaster':
                            teaserClass = 'icb-boardcast-icon';
                            break;
                        case 'SMS Broadcast':
                            teaserClass = 'sms-boardcast-icon';
                            break;
                        case 'SNS':
                            teaserClass = 'sns-boardcast-icon';
                            break;
                        case 'Wrong IVR':
                            teaserClass = 'wrongivr-boardcast-icon';
                            break;
                        case 'Wrong Star(*)':
                            teaserClass = 'wrongstar-boardcast-icon';
                            break;
                        default :
                            teaserClass = "";
                            break;
                    }

                    return teaserClass;
                },
                setValue: function (from, to) {


                    $.ajax({
                        url: options.url,
                        type: 'GET',
                        dataType: "json",
                        data: {from: from, to: to, CountryID : CountryID, OperatorID : OperatorID,ServiceID:ServiceID,PromotionID:PromotionID,PromotionText:PromotionTextValue},
                        contentType: "application/json; charset=utf-8",
                        beforeSend: function () {

                        },
                        complete: function () {

                        },
                        success: function (data) {
                            $.each(data, function (dataIndex, item) {

                              //  var targetTd = $container.find('table tr td[date="' + item.datefrom + '"]');
                                var targetTd = $container.find('table tr td[date="' + item.datefrom + '"]');

                                if (targetTd.length > 0) {

                                    if (item.dateadded != null && item.promotion != null ) {
                                        var eventPlaceHolder = targetTd.find('.event-placeholder');

                                        if (!targetTd.hasClass('drop-event')) {
                                            targetTd.addClass('drop-event');
                                        }

                                        var teaserIconSpan = $('<span class="teaser-icon"/>');

                                        teaserIconSpan.attr('id', item.broadcastingid);
                                        teaserIconSpan.attr('title-data', item.quantity);
                                        teaserIconSpan.attr('title-data-text', encodeURIComponent(item.promotion));


                                        if($('#broadcasting-calendar-country-filter').val()>0) {
                                            var teaserClass = self.getTeaserClass(item.promotion);
                                        }

                                        teaserIconSpan.attr('type', item.promotion);

                                        teaserIconSpan.addClass(teaserClass);

                                        eventPlaceHolder.append(teaserIconSpan);
                                        if(PromotionTextValue!='') {
                                            eventPlaceHolder.parents('.drop-event').css('background-color', 'rgba(254,0,0,0.34) !important')
                                        }
                                    }
                                }
                            });

                            if ($triggerControl != null) {
                                $triggerControl.trigger('click');
                                $triggerControl = null;
                            }

                            if(options.hasOwnProperty("CallBack"))
                                options.CallBack();
                        }
                    });
                },

                setHTML: function(){




                    $('#broadcasting-calendar-country-filter option:not(:selected)').removeAttr("selected");
                    $("#broadcasting-calendar-operator-filter option:not(:selected)").removeAttr("selected");
                    $("#broadcasting-calendar-select-service option:not(:selected)").removeAttr("selected");
                    $("#broadcasting-calendar-promotion-filter option:not(:selected)").removeAttr('selected')

                    $('#broadcasting-calendar-country-filter').find(":selected").attr("selected","selected");
                    $("#broadcasting-calendar-operator-filter").find(":selected").attr("selected","selected");
                    $("#broadcasting-calendar-select-service").find(":selected").attr("selected","selected");
                    $("#broadcasting-calendar-promotion-filter").find(":selected").attr("selected","selected");

                    PromotionTextValue = $('.search-promotion-text-field').val();

                   //$('.search-promotion-text-field').val(PromotionTextValue);

                    CountryID = $("#broadcasting-calendar-country-filter").find(":selected").val();
                    OperatorID = $("#broadcasting-calendar-operator-filter").find(":selected").val();
                    ServiceID = $("#broadcasting-calendar-select-service").find(":selected").val();
                    PromotionID = $("#broadcasting-calendar-promotion-filter").find(":selected").val();



                    CountryDataHTML = $("#broadcasting-calendar-country-filter")[0].outerHTML;
                    OperatorDataHTML = $("#broadcasting-calendar-operator-filter")[0].outerHTML;
                    ServiceDataHTML = $("#broadcasting-calendar-select-service")[0].outerHTML;
                    PromotionDataHTML = $("#broadcasting-calendar-promotion-filter")[0].outerHTML;
                }


            };

            var timerWasClicked = false;

            self.initialize();

        });
    };
})(jQuery);