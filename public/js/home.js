
    let arrayOrderSatatus = [];
    let strAgentCode = "<option value=''>All</option>";
   $( document ).ready(function() {
        
        getStatusOrders();
        getAgentCode();
        setTimeout(() => {
            getData('Search', 1)
        }, 1000);   
    });

    function getAgentCode(){
        $.ajax({
            url: sub_url+"/getAgentCode",
            type: "POST",
            data:   {
                        _token   : $("input[name='_token']").val(),
                    },
            dataType:"json",
            success: function(res){
                res.forEach(function (val) {
                    strAgentCode += "<option value='"+val["agnet_code"]+"'>"+val["agnet_code"]+" "+val["agent_firstname"]+" "+val["agent_lastname"]+"</option>"
                });
                $("#agent_code").html(strAgentCode);

            }
        });
    }

    function getStatusOrders(){
        $.ajax({
            url: sub_url+"/getStatusOrders",
            type: "POST",
            data:   {
                        _token   : $("input[name='_token']").val()
                    },
            dataType:"json",
            success: function(res){                
                res.map(function(val, key) {
                    arrayOrderSatatus[val["orders_status_key"]] = {"value": val["orders_status_desc"], "color" : val["orders_status_color"]}
                });
                var selectOptions = '<option value="">All Status</option>';
                for (var key in arrayOrderSatatus) {
               
                    selectOptions += '<option value="'+ key + '">' + arrayOrderSatatus[key]["value"] + '</option>';
                }
                $("#status").html(selectOptions);
            }

        });
    }

    function getData(caseType, page = 1){
        let cus_name    = $("#customer").val();
        let phone_no    = $("#phone_no").val();
        let id_card     = $("#id_card").val();
        let email       = $("#email").val();
        let s_coverage_end  = $("#s_coverage_end").val();
        let e_coverage_end  = $("#e_coverage_end").val();
        let iemi        = $("#iemi").val();
        let status      = $("#status").val();
        let order_code  = $("#order_code").val();
        let agent_code  = $("#agent_code").val();       

        $.ajax({
            url: sub_url+"/getCusDataList",
            type: "POST",
            data:   {
                        _token   : $("input[name='_token']").val(),
                        caseType : caseType, 
                        cus_name : cus_name,
                        phone_no : phone_no,
                        id_card  : id_card,
                        email    : email,
                        iemi     : iemi,
                        status   : status,
                        s_coverage_end : s_coverage_end,
                        e_coverage_end : e_coverage_end,
                        order_code  : order_code,
                        agent_code  : agent_code,
                        page : page
                    },
            dataType:"json",
            success: function(res){
                let table
                $("#dataRow").html("");
                res.data.forEach(function (val) {
                    table += "<tr class='"+arrayOrderSatatus[val["status"]]["color"]+"'>"
                    table += "  <td><a class='' target='_blank' href='"+sub_url+"/editOrder/"+val["orders_id"]+"' style='cursor: pointer; color: #0642ff;'><i class='fa-solid fa-pen-to-square fs18'></i></a></td>"
                    table += "  <td>"+val["orders_code"]+"</td>"
                    table += "  <td>"+val["customer_firstname"]+" "+val["customer_lastname"]+"</td>"
                    table += "  <td>"+val["customer_phone"]+"</td>"
                    // table += "  <td>"+val["customer_address"]+"</td>"
                    table += "  <td>"+val["customer_email"]+"</td>"
                    table += "  <td>"+val["mobile_model"]+" "+val["mobile_memory"]+" "+val["mobile_color"]+"</td>"
                    table += "  <td class='text-center'>"+val["coverage_end"]+"</td>"
                    table += "  <td class='text-center'>"+val["mobile_imei"]+"</td>"
                    table += "  <td class='text-center'>"+val["agent_code"]+"</td>"
                    table += "  <td class='text-center'>"+arrayOrderSatatus[val["status"]]["value"]+"</td>"
                    table += "</tr>"
                });
                $("#dataRow").html(table);
                let paginationLinks = generatePaginationLinks(res);
                $("#paginationLinks").html(paginationLinks);
            }
        })
    }


    function generatePaginationLinks(response) {

        let links = '';
    
        // Determine if the total number of pages is less than or equal to 10
        if (response.last_page <= 10) {
            // Generate links for all pages
            for (let i = 1; i <= response.last_page; i++) {
                let activeClass = i === response.current_page ? 'active' : '';
                links += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}" onclick="getData('Search', ${i})">${i}</a></li>`;
            }
        } else {
            // Determine the range of pages to display
            const startPage = Math.max(1, response.current_page - 2);
            const endPage = Math.min(response.last_page, response.current_page + 2);

            // Check if there are previous pages and generate 'Previous' link
            if (response.current_page > 1) {
                links += '<li class="page-item">';
                links += '<a class="page-link" href="#" data-page="' + (response.current_page - 1) + '" onclick="getData(\'Search\', ' + (response.current_page - 1) + ')">Previous</a>';
                links += '</li>';
            }

            // Generate link for the first page if necessary
            if (startPage > 1) {
                links += '<li class="page-item">';
                links += '<a class="page-link" href="#" data-page="1" onclick="getData(\'Search\', 1)">1</a>';
                links += '</li>';
                if (startPage > 2) {
                    links += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            // Generate page links
            for (let i = startPage; i <= endPage; i++) {
                let activeClass = i === response.current_page ? 'active' : '';
                links += '<li class="page-item ' + activeClass + '">';
                links += '<a class="page-link" href="#" data-page="' + i + '" onclick="getData(\'Search\', ' + i + ')">' + i + '</a>';
                links += '</li>';
            }

            // Generate link for the last page if necessary
            if (endPage < response.last_page) {
                if (endPage < response.last_page - 1) {
                    links += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                links += '<li class="page-item">';
                links += '<a class="page-link" href="#" data-page="' + response.last_page + '" onclick="getData(\'Search\', ' + response.last_page + ')">' + response.last_page + '</a>';
                links += '</li>';
            }

            // Check if there are more pages and generate 'Next' link
            if (response.current_page < response.last_page) {
                links += '<li class="page-item">';
                links += '<a class="page-link" href="#" data-page="' + (response.current_page + 1) + '" onclick="getData(\'Search\', ' + (response.current_page + 1) + ')">Next</a>';
                links += '</li>';
            }
        }
    
        return '<ul class="pagination">' + links + '</ul>';

    }