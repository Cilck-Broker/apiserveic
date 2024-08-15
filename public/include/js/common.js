 /*
	Project Name		:   SARF.LOS.WebApp
	Project Type		:	MVC
	Platform			:	JavaScript
	File Name			:	common.js
    Description         :   to put all common java script function

	Version				:	1.0.00
	Created Date        :	31 - Oct - 2017
	Complete Date		:	31 - Oct - 2017
	Developed By		:   SAW OO

	Updated Date		:   
	Updated By		    :   
	Detail Reason		:   

	Copyright (C) SilverlakeAxis 2017 All rights reserved.
*/

var LastPageURL = "../Home/Dashboard";
var LastDivUrl = "";
var _TradeDate = new Date();
var psMainPanel;
var _LoadingHtml = '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>';
var _ShowUrl = "N";

$(function () {
    //Number format
    NumberFormat();
         
    //date
    initDateTimePicker(TradeDate, DateFormat, DateLocale);

    //show notification
    if (typeof(msg) != "undefined" && msg != "") {
        if (typeof(msgType) == "undefined" || msgType == "") msgType = 'info';

        $.notify({
            message: msg
        }, {
            type: msgType,
            timer: 500,
            placement: {
                from: 'bottom',
                align: 'right'
            }
        });
    }

   
    //show session expired message
    CheckSessionExp();
    
    //show error message    
    if (typeof (ErrMsg) != "undefined" && ErrMsg != "") {
        ShowNotification(ErrMsg, "danger");
        ErrMsg = "";
    }

    //tooltip
    $('[rel="tooltip"]').tooltip();

    //select recent menu
    var selectedNode = $('a[href$="' + location.pathname + '"]').first();
    if (selectedNode.length > 0) {
        selectedNode[0].parentNode.className = "active";
        selectedNode.closest("div").collapse("show");
    }
    
    //Screen Mode check readonly
    if (location.href.indexOf("Mode=R") > 0) {
        DisableScreen();
    }

    //select side bar
    setNavigation();

    //scroll to top after page loaded
    ScrollToTop('.main-panel');

    //show current location
    $("#currLocation").html(window.location.pathname);

    GetTradeDate(TradeDate);

    ChangeCheckboxClass();

    ShowLoading(false);

});

function GetTradeDate(dtTradeDate) {
    dtTradeDate = (dtTradeDate) ? dtTradeDate.split(' ')[0].split('-') : '';
    _TradeDate = (dtTradeDate) ? new Date(dtTradeDate[0], dtTradeDate[1] - 1, dtTradeDate[2]) : new Date();
}

function GetStringToDate(dtDate) {
    dtDate = (dtDate) ? dtDate.split(' ')[0].split('/') : '';
    return (dtDate) ? new Date(dtDate[2], dtDate[1] - 1, dtDate[0]) : new Date();
}

function NumberFormat() {
    //Number format
    $("input.money").autoNumeric('init');        
    $("input.moneyneg").autoNumeric({ aNeg: '-' });
    $("input.num").autoNumeric({ mDec: 0 });
    $("input.interest").autoNumeric({ mDec: 6, aSep: '' });
    $("input.dec5").autoNumeric({ mDec: 5, aPad: 'true', aSep: '' });//5 decimal
    $("input.dec7").autoNumeric({ mDec: 7, aPad: 'true', aSep: '' });//5 decimal
    $("input.perc").autoNumeric({ aPad: 'true', mDec: 2, aSep: '', vMin: '0', vMax: '100' });
    $("input.percnge").autoNumeric({ aPad: 'true', mDec: 2, aSep: '', aNeg: '-', vMin: '0', vMax: '100' });    
    $("input.dec4").autoNumeric({ mDec: 4, aPad: 'true',aSep: '' });//4 decimal
    $("input.int").autoNumeric({ mDec: 0, aSep:'' });
    $("input.dec").autoNumeric({ mDec: 2, aSep: '' });
    $("input.decnge").autoNumeric({ mDec: 2, aSep: '', aNeg: '-' });
}

function DisableScreen(divName) {
    if (divName == null) divName = "";
    if (divName == "") {
        $("input, select, textarea").prop('disabled', true);
        $(".readonly-hide").hide();
        $(".card-header").attr('data-background-color', 'gray');
    }
    else {
        $("#" + divName).find("input, select, textarea").prop('disabled', true);
        $("#" + divName).find(".readonly-hide").hide();
        $("#" + divName).find(".card-header").attr('data-background-color', 'gray');
    }
}

function CheckSessionExp() {
    //show session expired message
    if (typeof (SessExpMsg) != "undefined" && SessExpMsg == "True") {
        if (!!navigator.userAgent.match(/Trident\/7\./)) {
        swal({
            title: _tslSessionExpired,
            text: _tslPleaseLogin,
            icon: "warning",
            button: "OK",
        }).then(function (ok) {
            if (ok) {
                ShowLoading(true);
                if (LoginFrom == "UCP")
                    location.href = location.href.replace(location.pathname, "") + "/Home/Login?UCP=Y";
                else
                    location.href = location.href.replace(location.pathname, "") + "/Home/Login";
            }
        });
    } else {
        var answer = window.confirm(_tslAreYouSure + "\n" + _tslDeleteMsg);
        if (answer == true) {
            ShowLoading(true);
            if (LoginFrom == "UCP")
                location.href = location.href.replace(location.pathname, "") + "/Home/Login?UCP=Y";
            else
                location.href = location.href.replace(location.pathname, "") + "/Home/Login";
        }
    }
}
}

function initialPartialView() {
    //Number format
    NumberFormat();

    //date
    initDateTimePicker(TradeDate, DateFormat, DateLocale);

    //tooltip
    $('[rel="tooltip"]').tooltip();
        
    //show session expired message
    CheckSessionExp();

    //show error message    
    if (typeof (ErrMsg) != "undefined" && ErrMsg != "") {
        ShowNotification(ErrMsg, "danger");
        ErrMsg = "";
    }

    //show notification
    if (typeof (msg) != "undefined" && msg != "") {
        if (typeof (msgType) == "undefined" || msgType == "") msgType = 'info';
        ShowNotification(msg, msgType);
        msg = "";
    }

    ShowLoading(false);
}

function setNavigation() {
    var path = decodeURIComponent(window.location.pathname.replace(/\/$/, ''));
    if ((path.split('/').length - 1) > 2)
        path = path.substring(0, path.split('/', 3).join('/').length);
        //path = '/' + path.split('/')[1] + '/' + path.split('/')[2];

    var selected = $('a[href^="' + path.toLowerCase() + '"]');
    if (selected.length > 0) {
        selected.closest('div').collapse('show');
        selected.closest('li').addClass('active');
    }
    else {
        path = window.location.href.replace(location.protocol + "//", "").replace(location.host, "");
        selected = $('a[href$="' + path + '"]');
        if (selected.length > 0) {
            selected.closest('div').collapse('show');
            selected.closest('li').addClass('active');
        }
    }
}

function padRight(number, width) {
    width -= number.toString().length;
    if (width > 0) {
        return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
    }
    return number + ""; // always return a string
}

function SelectSidebarMenu(url) {
    var selectedNode = $('a[href$="' + url + '"]').first();
    if (selectedNode.length > 0) {
        selectedNode[0].parentNode.className = "active";        
        selectedNode.closest("div").collapse("show");
    }
    else {
        selectedNode = $("a[onclick*='" + url + "']").first();
        if (selectedNode.length > 0) {
            $(".sidebar li").removeClass("active");
            if (selectedNode[0].parentNode.childNodes.length == 1 || selectedNode[0].parentNode.childNodes.length == 3) {
                selectedNode[0].parentNode.className = "active";
            }
            selectedNode.closest("div").collapse("show");
        }
    }
}

function SSBM(menuID) { //SelectSidebarMenu
    //setTimeout(500, function () {
        $(".sidebar li").removeClass("active");
        $("#" + menuID).addClass("active");
    //});
}

function ShowNotification(msg, type) {
    if (typeof (type) == "undefined" || type == "") type = 'info';
    CallResetTimer();
    $.notify({
        message: "<strong>" + msg + "</strong>"
    }, {
        type: type,
        timer: 500,
        placement: {
            from: 'bottom',
            align: 'right'
        }
    });
}

function ShowLoading(b) {
    CallResetTimer();
    if (b) {
        $("button:contains('Save')").prop('disabled', true);
        $("button:contains('Submit')").prop('disabled', true);
        $("body").addClass("loading");            
    }
    else {
        $("body").removeClass("loading");  
        $("button:contains('Save')").prop('disabled', false);
        $("button:contains('Submit')").prop('disabled', false);
    }
}

function ShowLoading2(b) {
    
    if (b) {
        $("button:contains('Save')").prop('disabled', true);
        $("body").addClass("loading");
    }
    else {
        $("body").removeClass("loading");
        $("button:contains('Save')").prop('disabled', false);
    }
}
 
function LoadPage(url, parm) {
    CallClearTimer();
    ShowLoading2(true);
    $("#currLocation").html(url);//show current location
    LastPageURL = url;//to refresh page    
    LastDivUrl = url;//to get last div url
    $("#divSearchModalBody").html("");//to clean prev search
    $("#divMainModalBody").html("");//to clean prev modal
    $("#hidSearchGroup").val("");//to clean search group
    if (parm == null || parm == "") parm = {};

    $.ajax({
        type: "GET",
        url: url,
        data: parm,
        contentType: "application/json; charset=utf-8",
        dataType: "html",
        cache: false,
        success: function successFunc(data) {     

            var body = $('<div />').append(data).html();
            if ($(body).find('#divPageBody').length > 0)
                $("#divPageBody").html($(body).find('#divPageBody')[0].outerHTML);
            else {
                $("#divPageBody").html("");
                $("#divPageBody").html(data);
            }
                                
            //paging click change ajax call
            if ($("#divPageBody .pagination").length > 0) {
                $("#divPageBody .pagination a").each(function (index, item) {
                    if (item.href != "" && item.onclick == null) {
                        item.setAttribute("onclick", "LoadPage('" + item.href + "');");
                        item.href = "#";
                    }
                });
            }

            //Number format
            NumberFormat();

            //date
            initDateTimePicker(TradeDate, DateFormat, DateLocale);

            //tooltip
            $('[rel="tooltip"]').tooltip();
            
            if (CurrLayout != "Layout") {
                SelectSidebarMenu(url);
                SetSelected(url);
            }
            
            //Screen Mode check readonly
            if (url.indexOf("Mode=R") > 0) {
                DisableScreen();
                ScreenMode = "";
            }

            //show session expired message
            CheckSessionExp();

            //show error message    
            if (typeof (ErrMsg) != "undefined" && ErrMsg != "") {
                ShowNotification(ErrMsg, "danger");
                ErrMsg = "";
            }

            //show notification
            if (typeof (msg) != "undefined" && msg != "") {
                if (typeof (msgType) == "undefined" || msgType == "") msgType = 'info';
                ShowNotification(msg, msgType);
                msg = "";
            }

            //scroll to top after page loaded
            ScrollToTop('.main-panel');
                        
            ChangeCheckboxClass();
                                    
            ShowLoading(false);
                     
        },
        error: function errorFunc(ex) {
            ShowNotification(GetErrDesc(ex), "danger");
            ShowLoading(false);
        }
    });
}

//scroll to top after page loaded
function ScrollToTop(obj) {           
    var isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;
    if (isWindows) {        
        if ($(obj).length > 0) {
            if (psMainPanel == null)
                psMainPanel = new PerfectScrollbar(obj);
            else {
                psMainPanel.element.scrollTop = 0;
                psMainPanel.update();
            }
        }
    }
}

function LoadAnnouncement(UserCode) {
    if (UserCode == "") return;
    $.ajax({
        type: "POST",
        url: "/Api/AnnouncementApi/GeAnnoucementListByUserID?UserCode=" + UserCode,
        dataType: "json",
        cache: false,
        success: function successFunc(data, status) {
            if (data.Status == "Succ") {
                if (data.Value1 != "") {
                    var mnuAnnoun = JSON.parse(data.Value1);
                    if (mnuAnnoun != null) {
                        if (mnuAnnoun.length == 0) {
                            $("#spnAnnuCount").hide();
                            $("#mnuAnnouncement").html("");
                        }
                        else {
                            var annMsg = "";
                            for (var i = 0; i < mnuAnnoun.length; i++) {
                                annMsg += "<li><a href='#' data-toggle='modal' data-target='#divMainModal' onclick=LoadAjaxDivAsync('divMainModalBody','../Admin/Announcement/Detail?id=" + mnuAnnoun[i].AnnounceID + "')>" + mnuAnnoun[i].Title + "</a></li>";
                            }
                            $("#spnAnnuCount").html(mnuAnnoun.length);
                            $("#spnAnnuCount").show();
                            $("#mnuAnnouncement").html(annMsg);
                            $("#mnuAnnouncement").show();
                        }
                    }
                }
            }
            else if (data.Status == "Err") {
                $("#spnAnnuCount").show();
                $("#spnAnnuCount").html("1")
                $("#mnuAnnouncement").html("<li><a href='#'>Err: " + data.ErrMessage + "</a></li>");
            }
        },
        error: function errorFunc(ex) {
            $("#spnAnnuCount").show();
            $("#spnAnnuCount").html("1")
            $("#mnuAnnouncement").html("<li><a href='#'>" + GetErrDesc(ex) + "</a></li>");
        }
    });
}

function LoadApplNote(ApplID) {
    if (ApplID == null || ApplID == "") return;
    $.ajax({
        type: "GET",
        url: "/Api/ApplNoteApi/GetApplNote?ApplID=" + ApplID,
        dataType: "json",
        cache: false,
        success: function successFunc(data, status) {
            if (data.Status == "Succ") {
                if (data.Value1 != "") {
                    var mnuAnnoun = JSON.parse(data.Value1);
                    if (mnuAnnoun != null) {
                        if (mnuAnnoun.length == 0) {
                            $("#spnAnnuCount").hide();
                            $("#mnuAnnouncement").html("");
                            $("#mnuAnnouncement").hide();
                        }
                        else {
                            var annMsg = "";
                            for (var i = 0; i < mnuAnnoun.length; i++) {
                                annMsg += "<li><a href='#' onclick='ShowModalDialog(\"\", \"/Appl/Application/FollowupIndex?ApplID=" + ApplID + "\")'>" + mnuAnnoun[i].Note + "</a></li>";
                            }
                            $("#spnAnnuCount").html(mnuAnnoun.length);
                            $("#spnAnnuCount").show();
                            $("#mnuAnnouncement").html(annMsg);
                            $("#mnuAnnouncement").show();
                        }
                    }
                }
            }
            else if (data.Status == "Err") {
                $("#spnAnnuCount").show();
                $("#spnAnnuCount").html("1")
                $("#mnuAnnouncement").html("<li><a href='#'>Err: " + data.ErrMessage + "</a></li>");
            }
        },
        error: function errorFunc(ex) {
            $("#spnAnnuCount").show();
            $("#spnAnnuCount").html("1")
            $("#mnuAnnouncement").html("<li><a href='#'>" + GetErrDesc(ex) + "</a></li>");
        }
    });
}

function LoadLayout(parm) {
    //A Activity Layout
    //M Main Layout
    

    if (parm == "M") {
        CurrLayout = "Layout";
        LoadAjaxDiv("divTopBar", "/Syst/Navbar/TopBar");
        LoadAjaxDiv("divSideBar", "/Syst/Navbar/Index");
        $(".fixed-plugin").hide();
    }
    else if (parm == "A") {
        CurrLayout = "LayoutActivity";
        LoadAjaxDiv("divTopBar", "/Syst/Navbar/TopActivity");
        LoadAjaxDiv("divSideBar", "/Syst/Navbar/Activity");
        $(".fixed-plugin").show();
    }

    //material sidebar start
    $sidebar = $('.sidebar');

    $.material.init();

    window_width = $(window).width();

    md.initSidebarsCheck();

    // check if there is an image set for the sidebar's background
    md.checkSidebarImage();
    //material sidebar end  
}

function LoadAjaxDiv(divName, url, parm) {
    CallResetTimer();
    ShowLoading(true);
    LastDivUrl = url;//to get last div url
    if (parm == null || parm == "") parm = {};

    $.ajax({
        type: "GET",
        url: url,
        data: parm,
        dataType: "html",
        async: false,
        cache: false,
        success: function successFunc(data) {
            $("#" + divName).html(data);  
            initialPartialView();
            ShowLoading(false);         
        },
        error: function errorFunc(ex) {
            $("#" + divName).html(GetErrDesc(ex));
            ShowLoading(false);
        }
    });
}

function ShowActivityHistory(url, title) {
    $("#divMainModalBody").html(_LoadingHtml);
    $("#mainModalTitle").html(title);
    if (url.indexOf("ApplCancel") > -1) {
        LoadAjaxDivAsync("divMainModalBody", url + "?ApplID=" + ApplID + "&ActivityName=" + ActivityName + "&Redirect=Y");
    }
    else if (url.indexOf("Promote") > -1) {
        LoadAjaxDivAsync("divMainModalBody", url + "&ApplID=" + ApplID + "&ActivityName=" + ActivityName);
    }
    else {
        LoadAjaxDivAsync("divMainModalBody", url + "?ApplID=" + ApplID);
    }
}

function ShowApplChecklist(url, title) {
    $("#divMainModalBody").html(_LoadingHtml);
    $("#mainModalTitle").html(title);
    LoadAjaxDivAsync("divMainModalBody", url + "?ApplID=" + ApplID + "&ActivityName=" + ActivityName);
}

function ShowMainModal(url, title) {
    $("#divMainModalBody").html(_LoadingHtml);
    $("#mainModalTitle").html(title);
    LoadAjaxDivAsync("divMainModalBody", url);
}

function ShowURL(url) {
    if (_Env == "DEV" && _ShowUrl == "Y") {
        return "<div class='card-footer'><div class='stats'>" + url + "</div></div>";
    }
    return "";
}

function LoadAjaxDivAsync(divName, url, parm ) {
    CallResetTimer();
    $("#" + divName).html(_LoadingHtml);
    LastDivUrl = url;//to get last div url
    if (parm == null || parm == "") parm = {};

    $.ajax({
        type: "GET",
        url: url,
        data: parm,
        //dataType: "html",
        cache: false,
        success: function successFunc(data) {            
            $("#" + divName).html(data + ShowURL(url));
            initialPartialView();
            
            //Screen Mode check readonly
            if (url.indexOf("Mode=R") > 0) {
                DisableScreen(divName);
                ScreenMode = "";
            }

            ChangeCheckboxClass();

            SetModalPagination("#" + divName);
        },
        error: function errorFunc(ex) {
            $("#" + divName).html(GetErrDesc(ex));
        }
    });
}

function SetModalPagination(div) {
    //paging click change ajax call
    if ($(div + " .pagination").length > 0) {
        $(div + " .pagination a").each(function (index, item) {
            if (item.href != "" && item.onclick == null) {
                item.setAttribute("onclick", "LoadModalAjaxDiv('" + div.replace("#","") + "','" + item.href + "');");
                item.href = "#";
            }
        });
    }
}

function LoadModalAjaxDiv(divName, url) {
    ShowLoading(true);
    LastDivUrl = url;//to get last div url
    $.ajax({
        type: "GET",
        url: url,
        data: {},
        dataType: "html",
        async: false,
        cache: false,
        success: function successFunc(data) {
            $("#" + divName).html("");
            $("#" + divName).html(data);
            SetModalPagination("#" + divName);

            //Screen Mode check readonly
            if (url.indexOf("Mode=R") > 0) {
                DisableScreen(divName);
            }

            //tooltip
            $('[rel="tooltip"]').tooltip();

            ChangeCheckboxClass();

            ScrollToTop('.main-panel');

            ShowLoading(false);
        },
        error: function errorFunc(ex) {
            $("#" + divName).html(GetErrDesc(ex));
            ShowLoading(false);
        }
    });
}

function SetSelected(path) {
    var url = path.toLowerCase();
    if (~url.indexOf('&'))
        url = url.substring(0, url.indexOf('&'));

    $('ul.nav[id!="leftNavBar"]').find('ul.nav a[onclick]').each(function () {
        var onclick = $(this).attr('onclick').toLowerCase()
            , _onclick = onclick.replace(/\..\//g, '').replace("')", '')
            , _url = url.replace(/\..\//g, '').replace(/^\//, '');

        if (~onclick.indexOf(url) && _onclick.lastIndexOf(_url) === (_onclick.length - _url.length)) {
            $(this).closest('div').collapse('show');
            $(this).closest('li').addClass('active');
            return false;
        }
    });
}

function GetPageNo(isDelete) {
    var content = $('div.card-content');
    if (isDelete)
        return ($(content).find('tbody tr:has(td)').length == 1) ? $(content).find('ul.pagination li.active a')[0].innerHTML - 1 : $(content).find('ul.pagination li.active a')[0].innerHTML;
    else
        return $(content).find('ul.pagination li.active a')[0].innerHTML;
}

function ShowLoader(show) {
    if (show)
        $(document.body).append($('<div>', { 'class': 'app-loader', 'style': 'display: block' }));
    else
        $('.app-loader').remove();
}
 
//Calculate datediff (days) 
function CalculateDays(sDate, eDate) {    
    var iDuration = 0;
    var sDate = $('#' + sDate).val();
    var eDate = $('#' + eDate).val();
    var dtSDate = formatString2Date(sDate);
    var dtEDate = formatString2Date(eDate);
    if (dtSDate != null && dtEDate != null) {
        iDuration = dateDiff(dtSDate, dtEDate);       
    }
    return iDuration;
}

function formatString2Date(sDate) {
    //source: "dd/mm/yyyy"
    var day = parseInt(sDate.substring(0, 2));
    var month = parseInt(sDate.substring(3, 5));
    var year = parseInt(sDate.substring(6, 10));
    return new Date(year, month, day);
}

function dateDiff(dt1, dt2) {
    if (dt1 != null && dt2 != null) {
        dt1.setHours(0);
        dt1.setMinutes(0, 0, 0);
        dt2.setHours(0);
        dt2.setMinutes(0, 0, 0);
        var dateDiff = Math.floor(dt2.getTime() - dt1.getTime()); // difference
        var iDay = Math.floor(dateDiff / (24 * 60 * 60 * 1000)); //Convert values days and return value

        if (iDay == 0)
            return 1;
        else if (iDay > 0)
            return iDay + 1;
        else if (iDay < 0)
            return iDay - 1;
    }
    return 0;
}

//textbox focus last position
function LastFocus(elem) {
    elem = document.getElementById(elem);
    if (elem != null) {
        var elemLen = elem.value.length;
        // For IE Only
        if (document.selection) {
            // Set focus
            elem.focus();
            // Use IE Ranges                                     
            var oSel = document.selection.createRange();
            // Reset position to 0 & then set at end
            oSel.moveStart('character', -elemLen);
            oSel.moveStart('character', elemLen);
            oSel.moveEnd('character', 0);
            oSel.select();
        }
        else if (elem.selectionStart || elem.selectionStart == '0') {
            // Firefox/Chrome
            elem.selectionStart = elemLen;
            elem.selectionEnd = elemLen;
            elem.focus();
        } // if
    }
} // SetCaretAtEnd()

function RefreshCurrPage() {
    if (LastPageURL != "") {
        LoadPage(LastPageURL);
        ShowNotification("<i class='fa fa-refresh fa-2x fa-spin' aria-hidden='true' style='color:white;' ></i>" + " " + LastPageURL, "info");
    }
}

function LoadDropDownList(GroupName, DropDownID, ParentDropDown) {
    if (ParentDropDown.value != null) {
        var url = "/Appl/DropDown/LoadHostDropDownList";
        var ParentValue = ParentDropDown.value;
        var DropDown = $("#" + DropDownID);
        var Selected = DropDown.val();
        var Found = false;
        DropDown.closest("div").append('<i id="spn' + DropDownID + '" class="fa fa-spinner fa-pulse fa-lg fa-fw"></i>');        
        DropDown.hide();    

        $.ajax({
            type: "GET",
            url: url,
            data: { GroupName: GroupName, FilterKey: ParentValue },
            dataType: "json",
            cache: false,
            success: function successFunc(response) {
                DropDown.empty();
                var length = response.length;
                $.each(response, function (index, item) {
                    if (Selected == item.Value) Found = true;
                    var p = new Option(item.Text, item.Value);
                    DropDown.append(p);
                    if (index === length - 1) {
                        $("#spn" + DropDownID).remove();
                        DropDown.show();
                    }
                });
                if (Found) DropDown.val(Selected);
            },
            error: function errorFunc(ex) {
                ShowNotification(GetErrDesc(ex), "danger");
                $("#spn" + DropDownID).remove();
                DropDown.show();
            }
        });             
    }
}


function GetParValue(Group, SourceID, DestinationID) {
    var surl = "/Appl/Front/GetParValue";
    var SourceCtl = $("#" + SourceID);
    var SelectedVal = SourceCtl.val();        

    $.ajax({
        type: "POST",
        url: surl + "?Group=" + Group + "&Code=" + SelectedVal,
        data: '{}',
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        cache: false,
        async: false,
        success: function (response) {            
            if (response.Status == "Succ") {                
                $("#" + DestinationID).val(response.Value1)
            }
        },
        error: function (ex) {
            ShowNotification(GetErrDesc(ex), "danger");
        }
    }); 
    
}


function LoadCustomDropDownList(GroupName, DropDownID, ParentDropDown, Key2, Key3, Key4, Key5) {
    if (ParentDropDown.value != null) {
        var url = "/Appl/DropDown/LoadCustomDropDownList";
        var ParentValue = ParentDropDown.value;
        var DropDown = $("#" + DropDownID);
        var Selected = DropDown.val();
        var Found = false;
        DropDown.closest("div").append('<i id="spn' + DropDownID + '" class="fa fa-spinner fa-pulse fa-lg fa-fw"></i>');
        DropDown.hide();
        if (Key2 == null) Key2 = "";
        if (Key3 == null) Key3 = "";
        if (Key4 == null) Key4 = "";
        if (Key5 == null) Key5 = "";

        $.ajax({
            type: "GET",
            url: url,
            data: { DDLName: GroupName, Key1: ParentValue, Key2: Key2, Key3: Key3, Key4: Key4, Key5: Key5 },
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            cache: false,
            success: function successFunc(response) {
                DropDown.empty();
                var length = response.length;
                $.each(response, function (index, item) {
                    if (Selected == item.Value) Found = true;
                    var p = new Option(item.Text, item.Value);
                    DropDown.append(p);
                    if (index === length - 1) {
                        $("#spn" + DropDownID).remove();
                        DropDown.show();
                    }
                });
                if (Found) DropDown.val(Selected);
            },
            error: function errorFunc(ex) {
                $("#spn" + DropDownID).remove();
                DropDown.show();
                ShowNotification(GetErrDesc(ex), "danger");
            }
        });
    }
}

function LoadDocTemplateDDL(DropDownID, ParentDropDown) {
    if (ParentDropDown.value != null && ParentDropDown.value != "") {
        var url = "/Appl/DropDown/LoadDocTemplateDDL";
        var ParentValue = ParentDropDown.value;
        var DropDown = $("#" + DropDownID);
        var Selected = DropDown.val();
        var Found = false;
        DropDown.closest("div").append('<i id="spn' + DropDownID + '" class="fa fa-spinner fa-pulse fa-lg fa-fw"></i>');
        DropDown.hide();

        $.ajax({
            type: "GET",
            url: url,
            data: { FilterKey: ParentValue },
            dataType: "json",
            cache: false,
            success: function successFunc(response) {
                DropDown.empty();
                var length = response.length;
                var d = new Option("Please Select...", "");
                DropDown.append(d);
                $.each(response, function (index, item) {
                    if (Selected == item.Value) Found = true;
                    var p = new Option(item.Value + " - " + item.Text, item.Value);
                    DropDown.append(p);
                    if (index === length - 1) {
                        $("#spn" + DropDownID).remove();
                        DropDown.show();
                    }
                });
                if (Found) DropDown.val(Selected);
            },
            error: function errorFunc(ex) {
                $("#spn" + DropDownID).remove();
                DropDown.show();
                ShowNotification(GetErrDesc(ex), "danger");
            }
        });
    }
}


function LoadTempTypeDLL(DropDownID, ParentDropDown) {
    if (ParentDropDown.value != null && ParentDropDown.value != "") {
        var url = "/Appl/DropDown/LoadTempType";
        var ParentValue = ParentDropDown.value;
        var DropDown = $("#" + DropDownID);
        var Selected = DropDown.val();
        var Found = false;
        DropDown.closest("div").append('<i id="spn' + DropDownID + '" class="fa fa-spinner fa-pulse fa-lg fa-fw"></i>');
        DropDown.hide();

        $.ajax({
            type: "GET",
            url: url,
            data: { FilterKey: ParentValue },
            dataType: "json",
            cache: false,
            success: function successFunc(response) {
                DropDown.empty();
                var length = response.length;
                var d = new Option("Please Select...", "");
                DropDown.append(d);
                $.each(response, function (index, item) {
                    if (Selected == item.Value) Found = true;
                    var p = new Option(item.Value + " - " + item.Text, item.Value);
                    DropDown.append(p);
                    if (index === length - 1) {
                        $("#spn" + DropDownID).remove();
                        DropDown.show();
                    }
                });
                if (Found) DropDown.val(Selected);
            },
            error: function errorFunc(ex) {
                $("#spn" + DropDownID).remove();
                DropDown.show();
                ShowNotification(GetErrDesc(ex), "danger");
            }
        });
    }
}

function LoadDocTempByGroupType(DropDownID, parGroup, parType) {
    if (DropDownID != null && parGroup != "" && parGroup != "") {
        var url = "/Appl/DropDown/LoadDocTempByGroupType";
        var parGroup = parGroup;
        var parType = parType.value;
        var DropDown = $("#" + DropDownID);
        var Selected = DropDown.val();
        var Found = false;
        DropDown.closest("div").append('<i id="spn' + DropDownID + '" class="fa fa-spinner fa-pulse fa-lg fa-fw"></i>');
        DropDown.hide();

        $.ajax({
            type: "GET",
            url: url,
            data: { group: parGroup, Type: parType},
            dataType: "json",
            cache: false,
            success: function successFunc(response) {
                DropDown.empty();
                var length = response.length;
                var d = new Option("Please Select...", "");
                DropDown.append(d);
                $.each(response, function (index, item) {
                    if (Selected == item.Value) Found = true;
                    var p = new Option(item.Value + " - " + item.Text, item.Value);
                    DropDown.append(p);
                    if (index === length - 1) {
                        $("#spn" + DropDownID).remove();
                        DropDown.show();
                    }
                });
                if (Found) DropDown.val(Selected);
            },
            error: function errorFunc(ex) {
                $("#spn" + DropDownID).remove();
                DropDown.show();
                ShowNotification(GetErrDesc(ex), "danger");
            }
        });
    }
}


function ShowModalDialog(title, url) {
    $("#mainModalTitle").html(title);
    LoadAjaxDivAsync("divMainModalBody", url);
    $("#divMainModal").modal();
}

function AddErrNotification(ErrMsg, ErrUrl) {
    var errHtml = "";
    $("#spnErrCount").addClass("hidden");
    if (ErrMsg != null && ErrMsg.length > 0) {
        $("#spnErrCount").html(ErrMsg.length);
        $("#spnErrCount").removeClass("hidden");
        var errHtml = "";
        for (var i = 0; i < ErrMsg.length; i++) {
            if (ErrUrl[i] != "") {
                errHtml += "<li><a href='#' onclick=LoadPage('" + ErrUrl[i] + "')>" + ErrMsg[i] + "</a></li>";
            }
            else {
                errHtml += "<li><a href='#'>" + ErrMsg[i] + "</a></li>";
            }
        }
        $("#mnuErrorList").html(errHtml);
    }
}

function getReadOnlyModeUrl() {    
    if (LastDivUrl.indexOf("Mode=R") > 0) {
        return "&Mode=R";
    }
    return "";
}

function CloseMainModalDialog() {
    
    if ($("#hidMainModal").val() == "CIFSearch") {
        LoadPage("/Appl/Customer/Index?ApplID=" + ApplID);
        $("#hidMainModal").val("");
    }
    else if ($("#hidMainModal").val() == "DisbursementSubmit") {
        AccountNoOnchange();
    }
}

function IsIE10() {
    if (navigator.appVersion.indexOf("MSIE 10") !== -1) {
        return true;
    }
    else
        return false;
}


function isInternetExplorer() {
    var ua = window.navigator.userAgent;
    //  alert(ua);
    var msie = ua.indexOf("MSIE ");
    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
        return true; // If Internet Explorer, return version number
    }

    //alert("not ie");
    return false;
}



function ChangeCheckboxClass() {
    if (IsIE10()) {        
        $("div.checkbox").removeClass("checkbox");
        $("div.radio").removeClass("radio");
    }
}

//------ cheng ---
function CallResetTimer() {
    var papa = this;
    var bfound = false;
    var bLoop = true;

    try {
        while (bLoop) {
            if (papa.document.getElementById('divSideBar') != null) {
                bfound = true; bLoop = false; break;
            }

            if (papa == papa.parent) bLoop = false;
            papa = papa.parent;
        }
        if (bfound == true) {
            papa.ResetTimerFlag();
        }
    }
    catch (ex) {

    } 

}


function CallClearTimer() {
    var papa = this;
    var bfound = false;
    var bLoop = true;

    try {
        while (bLoop) {
            if (papa.document.getElementById('divSideBar') != null) {
                bfound = true; bLoop = false; break;
            }

            if (papa == papa.parent) bLoop = false;
            papa = papa.parent;
        }
        if (bfound == true) {
            papa.ClearTimer();
        }
    }
    catch (ex) {

    }

}

function ScrollToTop2(div) {
    var elmnt = document.getElementById(div);
    elmnt.scrollIntoView()
}

function CallServices(url) {//just call service
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        cache: true,
        success: function successFunc(data, status) {             
        },
        error: function errorFunc(err) {                         
        }
    });
}

function GetErrDesc(ex) {
    var ErrDesc = "";
    if (ex.responseText) {
        var objResModel = jQuery.parseJSON(ex.responseText);
        if (objResModel && objResModel.Status === "Err") {
            ErrDesc = objResModel.ErrMessage;
        }
        else
            ErrDesc = ex.status + " - " + ex.statusText;
    }
    else
        ErrDesc = ex.status + " - " + ex.statusText;

    ErrDesc = "<h4 class=\"text-danger\">" + _ErrUnexpect + "</h4><p class=\"text-secondary\">" + ErrDesc + "</p>";

    return ErrDesc;
}

function GenerateApprovalDoc(objApplID) {
    ShowLoading(true);
    $.ajax({
        type: "GET"
        , url: "/Appl/ApplDocGen/GeneratePDF?ApplID=" + objApplID
        , data: {}
        , cache: false
        , success: function (result) {
            if (result.Status == 'Succ') {
                var FileName = result.Value1;
                var arrFacID = result.Value2;
                if (FileName && arrFacID) {
                    arrFacID = arrFacID.split(",");
                    for (var i = 0; i < arrFacID.length; i++) {
                        window.open("/Appl/ApplDocGen/DownloadDoc?FileName=" + FileName + " " + arrFacID[i] + ".pdf", '_blank');
                    }
                }
                else
                    ShowNotification("File not found.", 'danger');
            }
            else
                ShowNotification(result.ErrMessage, 'danger');
            ShowLoading(false);
        }
        , error: function (ex) {
            ShowNotification(GetErrDesc(ex), 'danger');
            ShowLoading(false);
        }
    });
}