var ordersid = $("#orders_id").val();
$(document).ready(function () {
    // getOrderDetail();
    getFollowupByOrderID(ordersid);
});


document.addEventListener('DOMContentLoaded', function () {
    $('#uploadFileOrder').on('submit', function(e){
        e.preventDefault(); 

        var data = new FormData(this);
        $.ajax({
            url: sub_url+"/uploadFileOrder",
            type: "POST",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (res) {
                // console.log(res);
                if (res.Code == 200) {
                    alert(res.message);
                    location.reload(true);
                } else {
                    alert(res.message);
                }
                return false;
            },
        });
    });        
});

function getOrderDetail() {
    let orders_id = $("#orders_id").val();
    $.ajax({
        url: sub_url+"/getOrderDetailById",
        type: "POST",
        data: {
            _token: $("input[name='_token']").val(),
            orders_id: orders_id,
        },
        dataType: "json",
        success: function (res) {
        },
    });
}

function insertFollowup() {
    // console.log("insertFollowup")
    var _token = $("#_token").val();
    var orders_id = $("#orders_id").val();
    var orders_followup_date = $("#orders_followup_date").val();
    var orders_followup_key = $("#orders_followup_key").val();
    var orders_followup_comment = $("#orders_followup_comment").val();
    var orders_followup_remind_date = $("#orders_followup_remind_date").val();
    var orders_followup_remind_note = $("#orders_followup_remind_note").val();
    // console.log(orders_followup_date)

    if (!orders_followup_date) {
        alert("Please Select Follow Date");
        $("#orders_followup_date").focus();
    } else if (!orders_followup_key) {
        alert("Please Select Follow Title");
        $("#orders_followup_key").focus();
    } else if (!orders_followup_comment) {
        alert("Please Input Comment");
        $("#orders_followup_comment").focus();
    } else {
        $.ajax({
            url: sub_url+"/insertFollowup",
            type: "POST",
            data: {
                _token: $("input[name='_token']").val(),
                orders_id: orders_id,
                orders_followup_date: orders_followup_date,
                orders_followup_key: orders_followup_key,
                orders_followup_comment: orders_followup_comment,
                orders_followup_remind_date: orders_followup_remind_date,
                orders_followup_remind_note: orders_followup_remind_note,
            },
            dataType: "json",
            success: function (res) {
                console.log(res.message);
                if (res.message) {
                    alert("Insert New Follow success")
                    getFollowupByOrderID(orders_id);
                } else {
                    alert("Please try Again");
                }
            },
        });
    }
}

function getFollowupByOrderID(orders_id) {
    // console.log("orders_id:" + orders_id);
    $("#orders_followup_key").val("");
    $("#orders_followup_comment").val("");
    $("#orders_followup_remind_date").val("");
    $("#orders_followup_remind_note").val("");
    $.ajax({
        url: sub_url+"/getFollowupByOrderID",
        type: "POST",
        data: {
            _token: $("input[name='_token']").val(),
            orders_id: orders_id,
        },
        dataType: "json",
        success: function (res) {
            // console.log(res);
            let data = "";
            res.map(function (val, key) {
                data += " <div class='boxFollowupShow mt10'>";
                data += " <div class='row'>";
                data += " <div class='col-md-7 col-sm-12'>";
                data +=" <div class='fs14 c3ca512'>" +val.follow_up_desc + "</div>";
                data += " <div class='fs12'>" +val.orders_followup_comment + "</div>";
                data += " <div class='fs10 c555555'><span >" +val.orders_followup_date +"</span> | <span >" +val.orders_followup_by + "</span></div>";
                data += " </div>";
                data += " <div class='col-md-5 col-sm-12'>";
                data += " <div> ";
                data += " <div class='fs10 c1641ff'>Remind Date:</div>";
                if (val.orders_followup_remind_date !== null) {
                    data +=" <div class='fs12'> " +val.orders_followup_remind_date + "</div>";
                } else {
                    data += " <div class='fs12'> - </div>";
                }
                data += " </div>";
                data += " <div>";
                data += " <div class='fs10 c1641ff'>Remind Note:</div>";
                if (val.orders_followup_remind_date !== null) {
                    data += " <div class='fs12'> " +val.orders_followup_remind_note + "</div>";
                } else {
                    data += " <div class='fs12'> - </div>";
                }
                data += " </div>";
                data += " </div>";
                data += " </div>";
                data += " </div>";
            });
            $("#boxFollowupShow").html(data);
        },
    });

    
}

function closeTab() {
    if(confirm("Are you sure you want to close windows?")){
        window.close(); 
    }
}


function saveCustomerData() {
    // Get form data
   
    var orders_id = $("#orders_id").val();
    var formData = {};
    var formElements = document.getElementById("CustomerData").elements;
    for (var i = 0; i < formElements.length; i++) {
        var element = formElements[i];
        if (element.type !== "button") {
            formData[element.name] = element.value;
        }
    }
    // console.log(formData);
    if(confirm("Are You Sure You Update Customer Profile?")){
        $.ajax({
            url: sub_url+"/updateCustomerData",
            type: "POST",
            data: {
                _token: $("input[name='_token']").val(),
                orders_id: orders_id,
                formData : formData
            },
            dataType: "json",
            success: function (res) {
                console.log(res);
                if (res.message) {
                    alert("Update Customer Profile success")
                    location.reload(true);
                } else {
                    alert("Please try Again");
                }
            },
        });
    }
}


function sendEmailComfirm(orderid) {
    // console.log(orderid);
    if(confirm("Are You Sure You Sending Email Comfirm?")){
    //     console.log(orderid);
        $.ajax({
            url: sub_url+"/sendEmailComfirm",
            type: "POST",
            data: {
                _token: $("input[name='_token']").val(),
                orderid: orderid,
                
            },
            // dataType: "json",
            success: function (res) {
                console.log(res);
                // if (res.message) {
                //     alert("Update Customer Profile success")
                //     location.reload(true);
                // } else {
                //     alert("Please try Again");
                // }
            },
        });
    }
}


function sendEmailRenew(orderid) {

    if(confirm("Are You Sure You Sending Email Comfirm?")){
        // console.log("sendEmailRenew"+orderid);
        $.ajax({
            url: sub_url+"/sendEmailRenew",
            type: "POST",
            data: {
                _token: $("input[name='_token']").val(),
                orderid: orderid,                
            },
            // dataType: "json",
            success: function (res) {
                console.log(res);
                if (res.message) {
                    alert("Sending Email Renew Complete")
                    location.reload(true);
                } else {
                    alert("Please try Again");
                }
            },
        });
    }
}

function btnCreateInvoice() {

    if(confirm("Are You Create Invoice")){
        console.log("btnCreateInvoice");
        var formData = {};
        var formElements = document.getElementById("comfirminvoice").elements;
        for (var i = 0; i < formElements.length; i++) {
            var element = formElements[i];
            if (element.type !== "button") {
                formData[element.name] = element.value;
            }
        }
        $.ajax({
            url: sub_url+"/btnCreateInvoice",
            type: "POST",
            data: {
                _token: $("input[name='_token']").val(),
                formData : formData
                
            },
            dataType: "json",
            success: function (res) {
                if (res.message) {
                    alert("Create Invoice Complete")
                    location.reload(true);
                } else {
                    alert("Please try Again");
                }
            },
        });
    }
}

function CancelInvoice(invoice_id,IVNumber) {
    if(confirm("Are You Sure Delete Invioce "+IVNumber+"?")){
        $.ajax({
            url: sub_url+"/CancelInvoice",
            type: "POST",
            data: {
                _token: $("input[name='_token']").val(),
                invoice_id: invoice_id,
                
            },
            dataType: "json",
            success: function (res) {
                console.log(res);
                if (res.code == 200) {
                    alert("Updated Cancel Invoice Complete")
                    location.reload(true);
                } else {
                    alert("Please try Again");
                }
            },
        });
    }
}

function CancelFile(orders_file_id){
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "คุณไม่สามารถย้อนกลับการดำเนินการนี้ได้!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ลบข้อมูล!',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {      
        if (result['value'] == true) {
            $.ajax({
                url: sub_url+"/DelOrdersFile",
                data:   {
                            _token : $("input[name='_token']").val(),
                            orders_file_id : orders_file_id,
                        },
                type: "POST",
                dataType: "json",
                success: function(res){
                    Swal.fire({
                        title: 'ลบข้อมูลเรียบร้อย!',
                        text: 'ข้อมูลของคุณได้ถูกลบแล้ว.',
                        type: 'success'
                    }).then((resu) => {
                        location.reload();
                    });
                }
            })
        }
      });
      
}




