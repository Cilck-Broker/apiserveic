
    $( document ).ready(function() {
        getInvoiceDataList();
    });

    function getInvoiceDataList(page = 1){
        Swal.fire({
            title: 'Processing...',
            html: '<div style="text-align:center; padding: 50px;"><img src="icon/Spinner-5.gif" alt="loading"/></div>',
            showConfirmButton: false
        });

        let invoice_code = $("#invoice_code").val();
        let s_paid_date  = $("#s_paid_date").val();
        let e_paid_date  = $("#e_paid_date").val();     

        $.ajax({
            url: sub_url+"/getInvoiceDataList",
            type: "POST",
            data:   {
                        _token       : $("input[name='_token']").val(),                        
                        invoice_code : invoice_code,
                        s_paid_date  : s_paid_date,
                        e_paid_date  : e_paid_date,
                        page         : page,
                    },
            dataType:"json",
            success: function(data){    
                $("#dataRow").html("");   
                let table = "";
                data.data.forEach(function (val) {
                    table += "<tr>"
                    table += "  <td>"+val["invoice_number"]+"</td>"
                    table += "  <td></td>"
                    table += "  <td>"+val["customer_firstname"]+"</td>"
                    table += "  <td>"+val["customer_lastname"]+"</td>"
                    table += "  <td>"+val["customer_idcard"]+"</td>"
                    table += "  <td>"+val["customer_address"]+"</td>"
                    table += "  <td>"+val["mobile_brand"]+"</td>"
                    table += "  <td>"+val["mobile_model"]+"</td>"
                    table += "  <td>"+val["mobile_model"]+"</td>"
                    table += "  <td>"+val["mobile_memory"]+"</td>"
                    table += "  <td>"+val["mobile_imei"]+"</td>"
                    table += "  <td></td>"
                    table += "  <td><nobr>"+val["renews_start_date"]+"</nobr></td>"
                    table += "  <td><nobr>"+val["renews_end_date"]+"</nobr></td>"
                    table += "  <td class='text-right'>"+parseFloat(val["second_hand_price"]).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })+"</td>"
                    table += "  <td class='text-right'>"+parseFloat(val["package_totalpremium"]).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })+"</td>"
                    table += "  <td class='text-right'>"+parseFloat(val["payment_net"]).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })+"</td>"
                    table += "  <td class='text-right'>"+parseFloat(val["patment_vat"]).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })+"</td>"
                    table += "  <td class='text-right'>"+parseFloat(val["package_premiumafterdisc"]).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })+"</td>"
                    table += "  <td>"+val["charge_id"]+"</td>"
                    table += "  <td>Renew</td>"
                    table += "  <td><nobr>"+val["paid_date"]+"</nobr></td>"
                    
                    table += "</tr>"
                });
                
                $("#dataRow").html(table);   
                
                if(data.total && data.per_page) {
                    const paginationHTML = createPagination(data);
                    $("#paginate").html(paginationHTML);
                }

                Swal.close();
            }
        })
    }

    function createPagination(response) {
        let links = '';
    
        // Determine if the total number of pages is less than or equal to 10
        if (response.last_page <= 10) {
            // Generate links for all pages
            for (let i = 1; i <= response.last_page; i++) {
                let activeClass = i === response.current_page ? 'active' : '';
                links += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}" onclick="getInvoiceDataList(${i})">${i}</a></li>`;
            }
        } else {
            // Determine the range of pages to display
            const startPage = Math.max(1, response.current_page - 2);
            const endPage = Math.min(response.last_page, response.current_page + 2);

            // Check if there are previous pages and generate 'Previous' link
            if (response.current_page > 1) {
                links += '<li class="page-item">';
                links += '<a class="page-link" href="#" data-page="' + (response.current_page - 1) + '" onclick="getInvoiceDataList(' + (response.current_page - 1) + ')">Previous</a>';
                links += '</li>';
            }

            // Generate link for the first page if necessary
            if (startPage > 1) {
                links += '<li class="page-item">';
                links += '<a class="page-link" href="#" data-page="1" onclick="getInvoiceDataList(1)">1</a>';
                links += '</li>';
                if (startPage > 2) {
                    links += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            // Generate page links
            for (let i = startPage; i <= endPage; i++) {
                let activeClass = i === response.current_page ? 'active' : '';
                links += '<li class="page-item ' + activeClass + '">';
                links += '<a class="page-link" href="#" data-page="' + i + '" onclick="getInvoiceDataList(' + i + ')">' + i + '</a>';
                links += '</li>';
            }

            // Generate link for the last page if necessary
            if (endPage < response.last_page) {
                if (endPage < response.last_page - 1) {
                    links += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                links += '<li class="page-item">';
                links += '<a class="page-link" href="#" data-page="' + response.last_page + '" onclick="getInvoiceDataList(' + response.last_page + ')">' + response.last_page + '</a>';
                links += '</li>';
            }

            // Check if there are more pages and generate 'Next' link
            if (response.current_page < response.last_page) {
                links += '<li class="page-item">';
                links += '<a class="page-link" href="#" data-page="' + (response.current_page + 1) + '" onclick="getInvoiceDataList(' + (response.current_page + 1) + ')">Next</a>';
                links += '</li>';
            }
        }
    
        return '<ul class="pagination">' + links + '</ul>';
    }
    

    function exportExcel(){
        Swal.fire({
            title: 'Processing...',
            html: '<div style="text-align:center; padding: 50px;"><img src="icon/Spinner-5.gif" alt="loading"/></div>',
            showConfirmButton: false
        });

        let invoice_code = $("#invoice_code").val();
        let s_paid_date  = $("#s_paid_date").val();
        let e_paid_date  = $("#e_paid_date").val();     

        $.ajax({
            url: sub_url+"/exportExcelInvoice",
            type: "POST",
            data:   {
                        _token       : $("input[name='_token']").val(),                        
                        invoice_code : invoice_code,
                        s_paid_date  : s_paid_date,
                        e_paid_date  : e_paid_date,
                    },
            dataType:"json",
            success: function(res){                
                Swal.close();
                if(res["status"] === "success") {
                    window.location.href = res["url"]; // กระตุ้นให้เบราว์เซอร์ดาวน์โหลดไฟล์
                }
            }
        })
    }