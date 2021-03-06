function exists(needle, haystack) {
    return ($.inArray(needle, haystack) > -1);
}
function changeLanguage(language, site_url) {

    var d = new Date();
    d.setTime(d.getTime() + (24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = BalancePlusLanguageCookieVariable+"=" + language + ";" + expires + ";path=/";
    document.location = "";
}

function ShowInputImage(input, id, height, width, callback) {
    if (typeof(width) == "undefined") {
        width = 150;
    }
    if (typeof(height) == "undefined") {
        height = 150;
    }
    if (typeof(id) == "undefined") {
        console.log("id must be defined!");
    }
    if (input.files && input.files[0]) {
        if ((input.files[0].size / 1024) < 5120) {
            if (jQuery.inArray(input.files[0].type, ["image/png", "image/jpeg", "image/jpg", "image/gif"]) >= 0) {
                $('#ProfilePicErrorUpload').addClass('hide');
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#' + id)
                        .attr('src', e.target.result)
                        .width(width)
                        .height(height);
                };

                reader.readAsDataURL(input.files[0]);

                return true;
            }
            else {
                $('#message-dialog-ProfilePicErrorUpload').html("File Format not supported. Please upload png/jpg/jpeg/gif file.");
                $('#ProfilePicErrorUpload').removeClass('hide');
                return false;
            }
        }
        else {
            $('#message-dialog-ProfilePicErrorUpload').html("Cannot upload image greater than 5MB.");
            $('#ProfilePicErrorUpload').removeClass('hide');
            return false;
        }
    }
    if (typeof(callback) != "undefined") {
        eval(callback);
    }

}
function showAddNewForm(title, url, width, height, data) {
    var formDiv = $('<div></div>');
    if (data === "undefined") {
        data = null;
    }
    window.newFormDialog = formDiv.dialog({
        width: width,
        height: height,
        title: title,
        modal: true,
        close: function () {
            removeDialog(this);
        },
        open: function () {
            var closeBtn = $('.ui-dialog-titlebar-close');
            closeBtn.html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text"></span>');
        }
    });

    var XHR = $.ajax({
        type: "get",
        url: url,
        data: {param: data},
        success: function (response) {
            if(XHR.getResponseHeader('Content-Length') == 0 )
                window.location = BASE_URL;

            formDiv.html(response);
        }
    });
}

function showEditFormWithId(id, title, url, width, height, data) {
    editForm(title, url, width, height, data, id);
}

function editForm(title, url, width, height, data, id) {
    var formDiv = $('<div/>');
    formDiv.dialog({
        width: width,
        height: height,
        modal: true,
        title: title,
        position: 10,
        close: function () {
            removeDialog(this);
        },
        open: function () {
            var closeBtn = $('.ui-dialog-titlebar-close');
            closeBtn.html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
        },
        dialogClass: 'dlgfixed'
    });

    var XHR = $.ajax({
        type: "get",
        url: url,
        data: {'ID': id, 'param': data},
        success: function (response) {
            if(XHR.getResponseHeader('Content-Length') == 0 )
                window.location = BASE_URL;

            formDiv.html(response);
        }
    });
}

function showEditForm(thisObj, title, url, width, height, data) {
    var id;

    if ($(thisObj).closest('tr').length) {
        id = $(thisObj).closest('tr').attr('id');
    } else {
        id = $(thisObj).closest('div[data-id]').attr('data-id');
    }

    editForm(title, url, width, height, data, id);
}

function showPopUp(thisObj, title, url, width, height) {
    var datetime = $(thisObj).closest('tr').attr('id');

    var parameter = {'DateTime': datetime};

    if ($(thisObj).closest('tr').attr('Interval') != null) {
        parameter = extendJson(parameter, {'Interval': $(thisObj).closest('tr').attr('Interval')});
    }

    var formDiv = $('<div/>');
    window.newFormDialog = formDiv.dialog({
        width: width,
        height: height,
        title: title,
        close: function () {
            removeDialog(this);
        },
        open: function () {
            var closeBtn = $('.ui-dialog-titlebar-close');
            closeBtn.html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
        },
        dialogClass: 'dlgfixed'
    });

    $(".dlgfixed").center(false);

    var XHR = $.ajax({
        type: "get",
        url: url,
        data: parameter,
        success: function (response) {
            if(XHR.getResponseHeader('Content-Length') == 0 )
                window.location = BASE_URL;

            formDiv.html(response);
        }
    });
}


function newFormSave(thisObj, url, title, width, height) {
    var formId = $(thisObj).attr("id");
    $('#' + formId).validationEngine();
    var alertDiv = $("<div/>");

    var XHR = $.ajax({
        type: "POST",
        dataType: "json",
        url: "",
        data: $("#" + formId).serialize(),
        beforeSend: function () {
            $(alertDiv).html("please wait");
        },
        success: function (data) {
            if(XHR.getResponseHeader('Content-Length') == 0 )
                window.location = BASE_URL;

            $(alertDiv).html(data.message);
        }
    });
    var noticeDialog = alertDiv.dialog({
        width: width,
        height: height,
        title: title,
        buttons: {
            "Ok": function () {
                noticeDialog.dialog("close");
                newFormDialog.dialog("close");
                location.reload();
            }
        },
        close: function () {
            removeDialog(this);
        },
        open: function () {
            var closeBtn = $('.ui-dialog-titlebar-close');
            closeBtn.html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
        },
        dialogClass: 'dlgfixed'
    });
}
function removeDialog(thisObj) {
    $(thisObj).find('input').each(function () {
        $(this).trigger('removeErrorMessage');
    });

    $(thisObj).dialog('destroy').remove();
}

function closeDialog(thisObj) {
    $(thisObj).find('input').each(function () {
        $(this).trigger('removeErrorMessage');
    });

    $(thisObj).closest('.ui-dialog-content').dialog('close');

}


function AjaxConfirmation(methodCallBack, title, confirmMsg, OkLabel, CancelLabel) {

    if (OkLabel == null)
        OkLabel = "Yes";

    if (CancelLabel == null)
        CancelLabel = "No";

    var $confirmationDialogDiv = $("<div id='dialog-confirm' title='Confirm'><p>" + confirmMsg + "</p></div>");

    $confirmationDialogDiv.dialog({
        title: title,
        resizable: false,
        height: 'auto',
        width: 'auto',
        modal: true,
        open: function () {
            var closeBtn = $('.ui-dialog-titlebar-close');
            closeBtn.html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
        },
        buttons: [
            {
                text: OkLabel,
                click: function () {
                    $(this).dialog("close");
                    eval(methodCallBack);
                    $('#dialog-confirm').remove();
                }
            },
            {
                text: CancelLabel,
                click: function () {
                    $(this).dialog("close");
                    $('#dialog-confirm').remove();
                }
            }]
    }).parent().find(".ui-dialog-titlebar-close").click(function () {
        $('#dialog-confirm').remove();
    });
}

var confirmed = false;

function ConfirmationServiceOptions(obj, title, confirmMsg, OkLabel, CancelLabel, buttonActive) {

    if (OkLabel == null)
        OkLabel = "Yes";

    if (CancelLabel == null)
        CancelLabel = "No";


    if (!confirmed) {
        $('body').append("<div id='dialog-confirm'><p>" + confirmMsg + "</p>");

        $("#dialog-confirm").dialog({
            title: title,
            resizable: false,
            height: "auto",
            width: "auto",
            open: function () {
                var closeBtn = $('.ui-dialog-titlebar-close');
                closeBtn.html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
            },
            modal: true,
            buttons: [
                {
                    text: OkLabel,
                    id : "service-options-ok-button",
                    class : "btn btn-success btn-rounded btn-xs",
                    click: function () {
                        $(this).dialog("close");
                        $('#dialog-confirm').remove();
                        confirmed = true;
                        obj.click();
                    }
                },
                {
                    text: CancelLabel,
                    class : "btn btn-danger btn-rounded btn-xs",
                    click: function () {
                        $(this).dialog("close");
                        confirmed = false;
                        $('#dialog-confirm').remove();
                    }
                }
            ]

        }).parent().find(".ui-dialog-titlebar-close").click(function () {
            $('#dialog-confirm').remove();
        });

        if(!buttonActive)
            $("body").find("#service-options-ok-button").attr("disabled", "disabled");

        var checkOnInterval = window.setInterval(function(){
            var ajax = $.ajax({
                url : BASE_URL+"Service/CheckAllChanged",
                type : "GET",
                data : {id : $(obj).parents("tr").attr("id")},
                success : function(data){
                    var response = parseInt(data);
                        if(data == 0) {
                            $("body").find("#service-options-ok-button").removeAttr("disabled");
                            clearInterval(checkOnInterval);
                        }else
                            $("body").find("#service-options-ok-button").attr("disabled", "disabled");
                    }
                });
        }, 4000);
    }

    return confirmed;
}

function Confirmation(obj, title, confirmMsg, OkLabel, CancelLabel) {

    if (OkLabel == null)
        OkLabel = "Yes";

    if (CancelLabel == null)
        CancelLabel = "No";


    if (!confirmed) {
        $('body').append("<div id='dialog-confirm'><p>" + confirmMsg + "</p>");

        $("#dialog-confirm").dialog({
            title: title,
            resizable: false,
            height: "auto",
            width: "auto",
            open: function () {
                var closeBtn = $('.ui-dialog-titlebar-close');
                closeBtn.html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
            },
            modal: true,
            buttons: [
                {
                    text: OkLabel,
                    click: function () {
                        $(this).dialog("close");
                        $('#dialog-confirm').remove();
                        confirmed = true;
                        obj.click();
                    }
                },
                {
                    text: CancelLabel,
                    click: function () {
                        $(this).dialog("close");
                        confirmed = false;
                        $('#dialog-confirm').remove();
                    }
                }
            ]

        }).parent().find(".ui-dialog-titlebar-close").click(function () {
            $('#dialog-confirm').remove();
        });
    }

    return confirmed;
}

function ConfirmationTeaserManagement(obj, title, confirmMsg, OkLabel, CancelLabel, base) {

    if (OkLabel == null)
        OkLabel = "Yes";

    if (CancelLabel == null)
        CancelLabel = "No";


    if (!confirmed) {
        $('body').append("<div id='dialog-confirm'><p>" + confirmMsg + "</p>");

        $("#dialog-confirm").dialog({
            title: title,
            resizable: false,
            height: "auto",
            width: "auto",
            open: function () {
                var closeBtn = $('.ui-dialog-titlebar-close');
                closeBtn.html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
            },
            modal: true,
            buttons: [
                {
                    text: OkLabel,
                    click: function () {

                        if(confirmMsg == "There is no balance criteria in teaser. Please add at least one") {
                            var id = $(obj).closest("td").closest("tr").attr("id");

                            window.location.href = base+"TeaserManagement/TeaserEdit?TeaserID="+id;
                        }else{
                            $(this).dialog("close");
                            $('#dialog-confirm').remove();
                            confirmed = true;
                            obj.click();
                        }
                    }
                },
                {
                    text: CancelLabel,
                    click: function () {
                        $(this).dialog("close");
                        confirmed = false;
                        $('#dialog-confirm').remove();
                    }
                }
            ]

        }).parent().find(".ui-dialog-titlebar-close").click(function () {
            $('#dialog-confirm').remove();
        });
    }

    return confirmed;
}


Date.prototype.addDays = function (days) {
    this.setDate(this.getDate() + days);
    return this;
};

Date.prototype.minusDays = function (days) {
    this.setDate(this.getDate() - days);
    return this;
};

Date.prototype.YmdFormat = function () {
    var yyyy = this.getFullYear().toString();
    var mm = (this.getMonth() + 1).toString(); // getMonth() is zero-based
    var dd = this.getDate().toString();

    return yyyy + '-' + (mm[1] ? mm : "0" + mm[0]) + '-' + (dd[1] ? dd : "0" + dd[0]); // padding
};


function DisplayPercentBar(tableId, columnIndex, maxWidth) {
    $("#" + tableId + " tbody tr td:nth-child(" + columnIndex + ")").each(function () {
        var cell = $(this);
        var actualContent = cell.text();

        if (actualContent == "")
            actualContent = "0.00";

        var percent = parseFloat(actualContent.replace('%', '').trim());
        var width = 1;
        if (!isNaN(percent) && percent > 0) {
            var temp = percent;
            if (temp == 0.00)
                width = 1;
            else
                width = (Math.round(maxWidth * temp) / 100).toFixed(1);
        }
        // cell.html("<div style='display: inline-block;width:" + width + "px;background-color:#3fbb32;height:10px;'>" + actualContent + "</div>");
        var element = $("#AnimationBar").clone();
        cell.html(element);
        element.animate({"width": width}, 1000);
        element.after("<span>  " + actualContent + "%</span>");
    });
}

function extendJson(a, b) {
    for (var key in b)
        if (b.hasOwnProperty(key))
            a[key] = b[key];
    return a;
}


function Warning(title, message) {
    var $WarningDialogDiv = $("<div id='dialog-confirm' title='Warning'><p>" + message + "</p></div>");
    $WarningDialogDiv.dialog({
        title: title,
        resizable: false,
        height: 'auto',
        width: 'auto',
        modal: true,
        open: function () {
            var closeBtn = $('.ui-dialog-titlebar-close');
            closeBtn.html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
        },
        buttons: {
            Ok: function () {
                $(this).dialog("close");
            }
        }
    });
}

function editConfirmation(thisObj, title, url, confirmMsg, OkLabel, CancelLabel, height, width) {
    var id;
    var Yes = "Yes";
    var No = "No";
    var confirmMsgs = "Are you sure you want to edit?";

    if (confirmMsg != null) {
        confirmMsgs = confirmMsg;
    }
    if (OkLabel != null) {
        Yes = OkLabel;
    }
    if (CancelLabel != null) {
        No = CancelLabel;
    }


    if ($(thisObj).closest('tr').length) {
        id = $(thisObj).closest('tr').attr('id');
    } else {
        id = $(thisObj).closest('div[data-id]').attr('data-id');
    }

    var editFormCallback = 'showEditFormWithId(' + id + ', "' + title + '","' + url + '" , ' + height + ', ' + width + ')';

    AjaxConfirmation(editFormCallback, title, confirmMsgs, Yes, No);

}
function changeDialogHeight($element) {
    var uiDialog = $element.closest('.ui-dialog');
    uiDialog.css({
        'height': uiDialog.find('.ui-dialog-content').height()+42,
        'top': '0',
        'left': '0',
        'right': '0',
        'bottom': '0',
        'margin': 'auto'
    });
}

function ConfirmationAddReplace(obj, title, confirmMsg, OkLabel, CancelLabel, formID) {

    if (OkLabel == null)
        OkLabel = "Yes";

    if (CancelLabel == null)
        CancelLabel = "No";

    var value = "Add";
    $('body').append("<div id='dialog-confirm'><p>" + confirmMsg + "</p>");

    $("#dialog-confirm").dialog({
        title: title,
        resizable: false,
        height: "auto",
        width: "auto",
        open: function () {
            var closeBtn = $('.ui-dialog-titlebar-close');
            closeBtn.html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
        },
        modal: true,
        buttons: [
            {
                text: OkLabel,
                "class": 'btn btn-danger',
                click: function () {
                    $(this).dialog("close");
                    $('#dialog-confirm').remove();
                    value = "Replace";
                    $("#condition_add_replace").val(value);
                    $("#" + formID).submit();
                }
            },
            {
                text: CancelLabel,
                "class": 'btn btn-success',
                click: function () {
                    $(this).dialog("close");
                    value = "Add";
                    $('#dialog-confirm').remove();
                    $("#condition_add_replace").val(value);
                    $("#" + formID).submit();
                }
            }
        ]

    }).parent().find(".ui-dialog-titlebar-close").click(function () {
        $('#dialog-confirm').remove();
    });

    return value;
}


function IsModifiedLatestSummary(obj, title, confirmMsg, Latest, Summary) {


    if (Latest == null)
        Latest = "Latest";

    if (Summary == null)
        Summary = "Summary";

    var value = "Latest";
    $('body').append("<div id='dialog-confirm'><p>" + confirmMsg + "</p>");

    $("#dialog-confirm").dialog({
        title: title,
        resizable: false,
        height: "auto",
        width: "500",
        open: function () {
            var closeBtn = $('.ui-dialog-titlebar-close');
            closeBtn.html('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
        },
        modal: true,
        buttons: [
            {
                text: Latest,
                "class": 'btn btn-danger',
                click: function () {
                    $(this).dialog("close");
                    $('#dialog-confirm').remove();
                    value = Latest;
                    $("#LatestSummary").val(value);
                    $(obj).closest("form").submit();
                }
            },
            {
                text: Summary,
                "class": 'btn btn-success',
                click: function () {
                    $(this).dialog("close");
                    value = Summary;
                    $('#dialog-confirm').remove();
                    $("#LatestSummary").val(value);
                    var action = $(obj).closest("form").attr("action");
                    $(obj).closest("form").attr("action",action+"?summary=true");
                    $(obj).closest("form").submit();
                }
            }
        ]

    }).parent().find(".ui-dialog-titlebar-close").click(function () {
        $('#dialog-confirm').remove();
    });

    return value;
}

function get_browser_info(){
    var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if(/trident/i.test(M[1])){
        tem=/\brv[ :]+(\d+)/g.exec(ua) || [];
        return {name:'IE',version:(tem[1]||'')};
    }
    if(M[1]==='Chrome'){
        tem=ua.match(/\bOPR\/(\d+)/)
        if(tem!=null)   {return {name:'Opera', version:tem[1]};}
    }
    M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
    return {
        name: M[0],
        version: M[1]
    };
}

function SetCookie(name, value){
    document.cookie=name+"="+value+";path=/";
}

function DeleteCookie(name){
    document.cookie=name+"=;path=/;expires=Thu, 01 Jan 1970 00:00:00 UTC";
}

function GetCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function setpopOver__($button, Type) {
    var title = $button.attr("title");
    var popoverscroll = "";
    if (Type == "Termless") {
        $style = 'style="width:233px; height:90px"';
    } else if (Type == "Calender") {
        $style = 'style="width:233px; height:310px"';
    } else {
        $style = 'style="min-width:130px; max-height:430px"';
        popoverscroll = "max-height: 310px; overflow-y:auto";
    }

    $button.popover({
        placement: 'left',
        template: '<div class="popover"' + $style + '><div class="arrow" ></div><h3 class="popover-title" style="text-align: center;"></h3><div class="popover-inner" style="' + popoverscroll + '"><div class="popover-content" >' +
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

    $button.attr("title", title);
}

function filterByService(object, table){
    var service = $(object).val();
    var table_ = $("#"+table);

    table_.trigger("refreshGrid", {
        serviceName : service
    });
}
