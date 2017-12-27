(function ($) {
    $.fn.validationEngineLanguage = function () {
    };
    $.validationEngineLanguage = {
        newLang: function () {
            $.validationEngineLanguage.allRules = {
                "required": { // Add your regex rules here, you can take telephone as an example
                    "regex": "none",
                    "alertText": "* This field is required",
                    "alertTextCheckboxMultiple": "* Please select an option",
                    "alertTextCheckboxe": "* This checkbox is required",
                    "alertTextDateRange": "* Both date range fields are required"
                },
                "requiredInFunction": {
                    "func": function (field, rules, i, options) {
                        return (field.val() == "test") ? true : false;
                    },
                    "alertText": "* Field must equal test"
                },
                "dateRange": {
                    "regex": "none",
                    "alertText": "* Invalid ",
                    "alertText2": "Date Range"
                },
                "dateTimeRange": {
                    "regex": "none",
                    "alertText": "* Invalid ",
                    "alertText2": "Date Time Range"
                },
                "minSize": {
                    "regex": "none",
                    "alertText": "* Minimum ",
                    "alertText2": " characters allowed"
                },
                "maxSize": {
                    "regex": "none",
                    "alertText": "* Maximum ",
                    "alertText2": " characters allowed"
                },
                "groupRequired": {
                    "regex": "none",
                    "alertText": "* You must fill one of the following fields"
                },
                "min": {
                    "regex": "none",
                    "alertText": "* Minimum value is "
                },
                "max": {
                    "regex": "none",
                    "alertText": "* Maximum value is "
                },
                "past": {
                    "regex": "none",
                    "alertText": "* Date prior to "
                },
                "future": {
                    "regex": "none",
                    "alertText": "* Date past "
                },
                "maxCheckbox": {
                    "regex": "none",
                    "alertText": "* Maximum ",
                    "alertText2": " options allowed"
                },
                "minCheckbox": {
                    "regex": "none",
                    "alertText": "* Please select ",
                    "alertText2": " options"
                },
                "equals": {
                    "regex": "none",
                    "alertText": "* Fields do not match"
                },
                "lessThan": {
                    "regex": "none",
                    "alertText": "* End Date must be greater <br/> than Start Date"
                },
                "creditCard": {
                    "regex": "none",
                    "alertText": "* Invalid credit card number"
                },
                "phone": {
                    // credit: jquery.h5validate.js / orefalo
                    "regex": /^([\+][0-9]{1,3}[\ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9\ \.\-\/]{3,20})((x|ext|extension)[\ ]?[0-9]{1,4})?$/,
                    "alertText": "* Invalid phone number"
                },
                "email": {
                    // HTML5 compatible email regex ( http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#    e-mail-state-%28type=email%29 )
                    "regex": /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
                    "alertText": "* Invalid email address"
                },
                "integer": {
                    "regex": /^[\-\+]?\d+$/,
                    "alertText": "* Not a valid integer"
                },
                "number": {
                    // Number, including positive, negative, and floating decimal. credit: orefalo
                    "regex": /^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/,
                    "alertText": "* Invalid number"
                },

                "number10Decimals2": {
                    "regex": /^-?\d{0,10}(\.\d{0,2})?$/,
                    "alertText": "* Maximum 12 characters allowed, Format (0.99)"
                },
                "number6Decimals4": {
                    "func": function (field) {
                        var fieldValue = field.val().toString().replace(/_|-/g, "");
                        if (fieldValue.indexOf('.') > -1) {

                            var SplitValue = fieldValue.split(".");

                            if (SplitValue[0].length > 4 || SplitValue[1].length != 6) {
                                return false;
                            }
                        } else {
                            if (fieldValue.length > 4) {
                                return false;
                            }
                        }
                        return true;
                    },

                    "alertText": "* Maximum 3 character before &  6 digits after point allowed",
                    "CustomAlertMessage": function (field) {

                        var fieldValue = field.val().toString().replace(/_|-/g, "");

                        if (fieldValue.indexOf('.') > -1) {

                            var SplitValue = fieldValue.split(".");

                            if (SplitValue[0].length > 4) {
                                return "* The value of the balance before the point should not exceed 4 characters";
                            }
                            if (SplitValue[1].length != 6) {
                                return "* The value of the balance after the point should be 6 characters";
                            }
                        } else {
                            if (fieldValue.length > 4) {
                                return "* Maximum 3 character before & 6 digits after point allowed";
                            }
                        }
                        return true;
                    }
                },
                "number6Decimals2": {
                    "func": function (field) {
                        var fieldValue = field.val().toString().replace(/_|-/g, "");
                        if (fieldValue.indexOf('.') > -1) {

                            var SplitValue = fieldValue.split(".");

                            if (SplitValue[0].length > 2 || SplitValue[1].length != 6) {
                                return false;
                            }
                        } else {
                            if (fieldValue.length > 2) {
                                return false;
                            }
                        }
                        return true;
                    },

                    "alertText": "* Maximum 6 character before &  2 digits after point allowed",
                    "CustomAlertMessage": function (field) {

                        var fieldValue = field.val().toString().replace(/_|-/g, "");

                        if (fieldValue.indexOf('.') > -1) {

                            var SplitValue = fieldValue.split(".");

                            if (SplitValue[0].length > 6) {
                                return "* The value of the balance before the point should not exceed 6 characters";
                            }
                            if (SplitValue[1].length > 2) {
                                return "* The value of the balance after the point should not exceed 2 characters";
                            }
                        } else {
                            if (fieldValue.length > 2) {
                                return "* Maximum 6 character before & 2 digits after point allowed";
                            }
                        }
                        return true;
                    }
                },
                "password": {
                    "regex": /^(?=.*\d)(?=.*[a-z])(?=.*(_|[^\w]))(?=.*[A-Z]).{9,20}$/,
                    "alertText": "Password should consist of <br/>" +
                    " ● atleast 9 characters<br/>" +
                    " ● atmax 20 characters<br/>" +
                    " ● atleast 1 Lowercase letter<br/>" +
                    " ● atleast 1 Uppercase letter<br/>" +
                    " ● atleast 1 Digit <br/>" +
                    " ● atleast 1 Symbol <br/>"
                },
                "date": {
                    //	Check if date is valid by leap year
                    "func": function (field) {
                        var pattern = new RegExp(/^(\d{4})[\/\-\.](0?[1-9]|1[012])[\/\-\.](0?[1-9]|[12][0-9]|6[01])$/);
                        var match = pattern.exec(field.val());
                        if (match == null)
                            return false;

                        var year = match[1];
                        var month = match[2] * 1;
                        var day = match[3] * 1;
                        var date = new Date(year, month - 1, day); // because months starts from 0.

                        return (date.getFullYear() == year && date.getMonth() == (month - 1) && date.getDate() == day);
                    },
                    "alertText": "* Invalid date, must be in YYYY-MM-DD format"
                },
                "ipv4": {
                    "regex": /^((([01]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))[.]){3}(([0-1]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))$/,
                    "alertText": "* Invalid IP address"
                },
                "url": {
                    "regex": /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,
                    "alertText": "* Invalid URL"
                },
                "onlyNumberSp": {
                    "regex": /^[0-9\ ]+$/,
                    "alertText": "* Numbers only"
                },
                "onlyLetterSp": {
                    "regex": /^[a-zA-Z\ \']+$/,
                    "alertText": "* Letters only"
                },
                "onlyLetterNumber": {
                    "regex": /^[0-9a-zA-Z]+$/,
                    "alertText": "* No special characters allowed"
                },
                // --- CUSTOM RULES -- Those are specific to the demos, they can be removed or changed to your likings
                "ajaxUsernameCallPhp": {
                    "url": "../Admin/AjaxValidation/UsernameCheck",
                    // you may want to pass extra data on the ajax call
                    "extraDataDynamic": [{'ParameterName': 'ID', 'ParameterValue': "#ID"}],
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertText": "* This Username is already taken",
                    "alertTextLoad": "* Validating, please wait"
                },
                "ajaxCountryNameCheck": {
                    "url": BASE_URL+"Country/CheckCountry",
                    // you may want to pass extra data on the ajax call
                    "extraDataDynamic": [{'ParameterName': 'id', 'ParameterValue': "#ID"}],
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertText": "* This Country Name Already Exit",
                    "alertTextLoad": "* Validating, please wait"
                },
                "ajaxOperatorNameCheck": {
                    "url": BASE_URL+"Operator/CheckOperator",
                    // you may want to pass extra data on the ajax call
                    "extraDataDynamic": [{'ParameterName': 'id', 'ParameterValue': "#ID"}],
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertText": "* This Operator Name Already Exit",
                    "alertTextLoad": "* Validating, please wait"
                },
                "ajaxServiceNameCheck": {
                    "url": BASE_URL+"Service/CheckService",
                    // you may want to pass extra data on the ajax call
                    "extraDataDynamic": [{'ParameterName': 'id', 'ParameterValue': "#ID"}],
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertText": "* This Service Name Already Exit",
                    "alertTextLoad": "* Validating, please wait"
                },
                "ajaxActiveServiceIDCallPhp": {
                    "url": BASE_URL + "AllocationCriteria/ActiveServiceIDCheck",
                    // you may want to pass extra data on the ajax call
                    "extraDataDynamic": [{'ParameterName': 'EditID', 'ParameterValue': "#EditID"}],
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertText": "* This Active Service ID is already taken",
                    "alertTextLoad": "* Validating, please wait"
                },
                // --- CUSTOM RULES -- Those are specific to the demos, they can be removed or changed to your likings
                "ajaxCountryNameCallPhp": {
                    "url": "Countries/CheckCountryName",
                    // you may want to pass extra data on the ajax call
                    "extraDataDynamic": [{'ParameterName': 'ID', 'ParameterValue': "#ID"}],
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertText": "* This Country already exists",
                    "alertTextLoad": "* Validating, please wait"
                },
                "ajaxSellerNameCallPhp": {
                    "url": "Sellers/CheckSellerName",
                    // you may want to pass extra data on the ajax call
                    "extraDataDynamic": [{'ParameterName': 'ID', 'ParameterValue': "#ID"}],
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertText": "* This seller already exists",
                    "alertTextLoad": "* Validating, please wait"
                },
                "ajaxProcudureNameCallPhp": {
                    "url": BASE_URL+"ReportData/CheckProcudureExist",
                    // you may want to pass extra data on the ajax call
                    "extraDataDynamic": [{'ParameterName': 'ID', 'ParameterValue': "#ID"}],
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertText": "* This procudure already exists",
                    "alertTextLoad": "* Validating, please wait"
                },
                "validate2fields": {
                    "alertText": "* Please input HELLO"
                },
                //tls warning:homegrown not fielded
                "dateFormat": {
                    "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:0?[1-9]|1[0-2])(\/|-)(?:0?[1-9]|1\d|2[0-8]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(0?2(\/|-)29)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$/,
                    "alertText": "* Invalid Date"
                },
                //tls warning:homegrown not fielded 
                "dateTimeFormat": {
                    "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])\s+(1[012]|0?[1-9]){1}:(0?[1-5]|[0-6][0-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+(am|pm|AM|PM){1}$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^((1[012]|0?[1-9]){1}\/(0?[1-9]|[12][0-9]|3[01]){1}\/\d{2,4}\s+(1[012]|0?[1-9]){1}:(0?[1-5]|[0-6][0-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+(am|pm|AM|PM){1})$/,
                    "alertText": "* Invalid Date or Date Format",
                    "alertText2": "Expected Format: ",
                    "alertText3": "mm/dd/yyyy hh:mm:ss AM|PM or ",
                    "alertText4": "yyyy-mm-dd hh:mm:ss AM|PM"
                },
                "multiplemsisdn": {
                    // HTML5 compatible email regex ( http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#    e-mail-state-%28type=email%29 )
                    "regex": /^(\d*,\s)*\d*$/,
                    "alertText": "* Invalid Format (required {msisdn}, {msisdn}, ..)"
                },
                "StopDate": {
                    "func": function () {
                        start = $("#start").val();
                        stop = $("#stop").val();
                        return stop > start;
                    },
                    "alertText": "* Stop time must be greater than start time."
                },
                "ajaxCheckUniqueBlacklist": {
                    "url": "Blacklist/checkNumberUniqueness",
                    // you may want to pass extra data on the ajax call
                    "extraDataDynamic": [{'ParameterName': 'value', 'ParameterValue': "#Value"}],
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertText": "* This number id already on blacklist",
                    "alertTextLoad": "* Validating, please wait"
                },
                "ajaxCheckUniqueWhitelist": {
                    "url": "Whitelist/checkNumberUniqueness",
                    // you may want to pass extra data on the ajax call
                    "extraDataDynamic": [{'ParameterName': 'value', 'ParameterValue': "#Value"}],
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertText": "* This number id already on whitelist",
                    "alertTextLoad": "* Validating, please wait"
                },
                "countmax": {
                    "func": function () {
                        $max = $("#Text").attr("data-max");
                        count = $("#Text").val().length;
                        return count <= $max;
                    },
                    "alertText": " * The number of symbols must not exceed :max symbols. There are :count symbols now.",
                    "CustomAlertMessage": function () {
                        alert = " * The number of symbols must not exceed :max symbols. There are :count symbols now."
                        return alert;
                    }
                },
                "countmaxWithRoaming": {
                    "func": function () {
                        $max = $("#Text").attr("data-max");
                        count = $("#Text").val().length;
                        return count <= $max;
                    },
                    "alertText": " * Roaming option is selected. Teaser length shouldn't exceed :max symbols",
                    "CustomAlertMessage": function () {
                        alert = " * Roaming option is selected. Teaser length shouldn't exceed :max symbols"
                        return alert;
                    }
                },
                "lessThanMax": {
                    "func": function () {
                        min = $("#min").val();
                        max = $("#max").val();
                        return parseFloat(min) < parseFloat(max);
                    },
                    "alertText": "Minimum value should be smaller than maximum."
                },
                "greaterThanMin": {
                    "func": function () {
                        min = $("#min").val();
                        max = $("#max").val();
                        return parseFloat(max) > parseFloat(min);
                    },
                    "alertText": "Maximum value should be greater than minimum."
                },
                "parameterwithNoValue": {
                    "func": function (field) {
                         str = field.val();

                        //min = $("#min").val();
                        //max = $("#max").val();
                        return str.indexOf("@") < 0;
                    },
                    "alertText": "Either Remove or Put the right value."
                },
                "CharValidation": {
                    "func": function () {
                        var text = $("#Text").val();
                        var LanguageType = parseInt($("#Text").parents("form").find("select[id='Language'] option:selected").val());
                        var isValid = true;

                        var Cyrillic = new RegExp('^[абвгдеёжзийcопрстуфхцчшщьыъэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯ]*$');
                        var Latin = new RegExp('^[a-zA-Z0-9]*$');
                        var validationArray = ['"', ";", ":", "'", ",", ".", "/", "?", "!", "|", "\\", "-", "=", "+", "*", "#", "%", "", "(", ")", "[", "]", " "];

                        for (var i = 0; i < text.length; i++) {
                            if (((text[i].charCodeAt(0) >= 128 && text[i].charCodeAt(0) <= 173) || Cyrillic.test(text[i]) || Latin.test(text[i]) || ($.inArray(text[i], validationArray) != -1) || (!isNaN(text[i])))) {

                                isValid = true;
                            } else {
                                isValid = false;
                                break;
                            }
                        }


                        return isValid;
                    },
                    "alertText": "The Character are not valid."
                },
                "CyrillicValidation": {
                    "func": function (field) {
                        var text = field.val();
                        var isValid = true;

                        var Cyrillic = new RegExp('^[абвгдеёжзийклмнопрстуфхцчшщьыъэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯ]*$');

                        for (var i = 0; i < text.length; i++) {
                            if (!Cyrillic.test(text[i])) {
                                isValid = true;
                            } else {
                                isValid = false;
                                break;
                            }
                        }


                        return isValid;
                    },
                    "alertText": "* Cyrillic symbols are not allowed"
                },
                "arabicValidation" : {
                    "func" : function(field){
                        var arabic = /[\u0600-\u06FF]/;
                        var text = field.val();

                        return !arabic.test(text);
                    },
                    "alertText": "* Arabic symbols are not allowed"
                },
                "serviceOption": {
                    "url": BASE_URL+"Service/CheckAvailable",
                    "extraData": "",
                    "extraDataDynamic": ['#service_name'],
                    "alertText": "* The service name that You want to add is already exists. Please enter a different service name",
                    "alertTextLoad": "* Validating, please wait"
                },
                "checkDateOrder": {

                    func: function (field) {

                        var dateTo = $("#DateTo").val();
                        var dateFrom = $("#DateFrom").val();

                        return !!((dateTo == 0 && dateFrom == 0) || (dateTo >= dateFrom ));
                    },
                    "alertText": "* Date To must be greater than Date From"

                },
                "checkServiceBroadcasting11": {

                    func: function (field) {

                        return true
                    },
                    "alertText": "* Date To must be greater than Date From"

                },

                "checkServiceBroadcasting": {
                    "url": BASE_URL+"BroadcastingCalendar/CheckService",
                    "extraDataDynamic": [{'ParameterName': 'Dates', 'ParameterValue': "#selectedDate"},{'ParameterName': 'Country', 'ParameterValue': "#countryID"},{'ParameterName': 'Operator', 'ParameterValue': "#operatorID"},{'ParameterName': 'Promotion', 'ParameterValue': "#promotion-dropdown"},{'ParameterName': 'ID', 'ParameterValue': "#ID"}],
                    "alertText": "* The promo that you want to add is already exists with one of the Date ,Country & Operator",
                    "alertTextLoad": "* Validating, please wait"
                },
                "changedServiceOption" : {
                    "func" : function(field){
                        var currentValue = field.val();
                        if(currentValue != "" && currentValue != GetOriginalServiceOptionText())
                            $('input[name="Submitx"]').removeAttr("disabled");
                        else
                            $('input[name="Submitx"]').attr("disabled", "disabled");

                        return true;
                    },
                    "alertText": ""
                }
            };
        }
    };

    $.validationEngineLanguage.newLang();

})(jQuery);
