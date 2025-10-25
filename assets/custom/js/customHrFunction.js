window.onload = function () {
    var pageType = $('#pageType').val();
}

var baseUrl = $('#baseUrl').val();
function deleteCompanyMasterTableRecord(url,id,tableName,companyId,accId) {

    swal({
        title: "Delete",
        text: "Are you sure you want to delete this record",
        buttons: true,
    }).then((isConfirm) => {
        if (isConfirm) {
            $.ajax({
                url: baseUrl+url,
                type: "GET",
                data: {companyId:companyId,id:id,tableName:tableName,accId:accId},
                success:function(data) {
                    location.reload();
                }
            });
        }
    });
}

function deleteLeaveApplicationData(companyId,recordId) {

    swal({
        title: "Delete",
        text: "Are you sure you want to delete this record",
        buttons: true,
    }).then((isConfirm) => {
        if (isConfirm) {
            $.ajax({
                url: ''+baseUrl+'/cdOne/deleteLeaveApplicationDetail',
                type: "GET",
                data: {companyId:companyId,recordId:recordId},
                success:function(data) {
                    location.reload();
                }
            });
        }
    });
}

var employee_id = $('#employee_id').val();
function approveAndRejectTableRecords(companyId, recordId, approval_status, tableName){

    $.ajax({
        url : ''+baseUrl+'/cdOne/approveAndRejectTableRecord',
        type: "GET",
        data: {'employee_id':employee_id,'request_type':'approve_reject',companyId: companyId, recordId: recordId, tableName: tableName, approval_status: approval_status},
        success: function (data) {
            console.log(data);
            if(data == 'error') {
                alert('Incorrect Approval Code');
            } else{
                location.reload();
            }
        },
        error: function () {
            console.log("error");
        }
    });
}

function printView(param1,param2,param3) {

    $('.table-responsive').removeClass('table-responsive');
    $('.wrapper').removeClass('wrapper');
    $( ".qrCodeDiv" ).removeClass( "hidden" );
    var printContents = document.getElementById(param1).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}


function printViewWithImagess(param1, param2, param3) {
    $('.table-responsive').removeClass('table-responsive');
    $('.wrapper').removeClass('wrapper');
    $(".qrCodeDiv").removeClass("hidden");

    // Check if the element with the specified ID exists
    var printElement = document.getElementById(param1);

    if (printElement) {
        // Create a new stylesheet for print media
        var printStyles = document.createElement('style');
        printStyles.innerHTML = `
            @media print {
                /* Style for the top image on each page */
                .top-image {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    text-align: center;
                    margin-bottom: 20px;
                    z-index: 1000;
                }
                /* Style for the bottom image on each page */
                .bottom-image {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    text-align: center;
                    margin-bottom: 20px;
                    z-index: 1000;
                }
            }
        `;
        document.head.appendChild(printStyles);

        // Add the top image to the printContents
        var topImage = `<div class="top-image"><img src="${param2}" alt="Top Image"></div>`;
        var bottomImage = `<div class="bottom-image"><img src="${param3}" alt="Bottom Image"></div>`;

        // Wrap each printed content block with top and bottom images
        var printContents = topImage + '<div class="page">' + printElement.innerHTML + '</div>' + bottomImage;

        document.body.innerHTML = printContents;

        // Wait for a short delay to ensure the content has been rendered before printing
        setTimeout(function () {
            window.print();
            location.reload();
        }, 1000); // Adjust the delay as needed
    } else {
        console.error(`Element with ID '${param1}' not found.`);
    }
}


function printViewWithImage(param1, param2, param3) {
    $('.table-responsive').removeClass('table-responsive');
    $('.wrapper').removeClass('wrapper');
    $(".qrCodeDiv").removeClass("hidden");

    // Create a new stylesheet for print media
    var printStyles = document.createElement('style');
    printStyles.innerHTML = `
        @media print {
            /* Style for the top image on each page */
            .top-image {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                text-align: center;
                margin-bottom: 20px;
                z-index: 1000;
            }
        }
    `;
    document.head.appendChild(printStyles);

    // Add the top image to the printContents
    var topImage = `<div class="top-image"><img src="${param2}" alt="Top Image"></div>`;

    // Get the content specified by param1
    var printContent = document.getElementById(param1);

    if (printContent) {
        // Combine the cover letter content, the top image, and the content specified by param1
        var printContents = topImage + printContent.innerHTML;

        document.body.innerHTML = printContents;

        // Wait for a short delay to ensure the content has been rendered before printing
        setTimeout(function () {
            window.print();
            location.reload();
        }, 1000); // Adjust the delay as needed
    } else {
        console.error(`Element with ID '${param1}' not found.`);
    }
}





//End Print

//Start Export
function exportView(param1,param2,$param3) {
    var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j = 0;
    tab = document.getElementById(param1);//.getElementsByTagName('table'); // id of table
    if (tab==null) {
        return false;
    }
    if (tab.rows.length == 0) {
        return false;
    }

    var a= tab
    for (j = 0 ; j < a.rows.length ; j++)
    {

        if(a.rows[j].children[a.rows[j].children.length - 1 ].id == 'hide-table-row')
        {
            a.rows[j].removeChild(a.rows[j].children[[a.rows[j].children.length - 1 ]])
        }

        tab_text = tab_text + a.rows[j].innerHTML + "</tr>";
    }

    tab_text = tab_text + "</table>";
    tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
    tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
    document.getElementsByClassName('show_data').removeClass;

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html", "replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus();
        sa = txtArea1.document.execCommand("SaveAs", true, "download.xls");
    }
    else                 //other browser not tested on IE 11
    //sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        try {
            var blob = new Blob([tab_text], { type: "application/vnd.ms-excel" });
            window.URL = window.URL || window.webkitURL;
            link = window.URL.createObjectURL(blob);
            a = document.createElement("a");
            if (document.getElementById("caption")!=null) {
                a.download=document.getElementById("caption").innerText;
            }
            else
            {
                a.download = 'download';
            }

            a.href = link;

            document.body.appendChild(a);

            a.click();

            document.body.removeChild(a);
        } catch (e) {
        }


    return false;
    //return (sa);
}

jQuery.fn.tableToCSV = function() {
    var clean_text = function(text){
        text = text.replace(/"/g, '""');
        return '"'+text+'"';
    };

    $(this).each(function(){
        var table = $(this);
        var caption = $(this).find('caption').text();
        var title = [];
        var rows = [];

        $(this).find('tr').each(function(){
            var data = [];
            $(this).find('th').each(function(){
                var text = clean_text($(this).text());
                title.push(text);
            });
            $(this).find('td').each(function(){
                var text = clean_text($(this).text());
                data.push(text);
            });
            data = data.join(",");
            rows.push(data);
        });
        title = title.join(",");
        rows = rows.join("\n");

        var csv = title + rows;
        var uri = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
        var download_link = document.createElement('a');
        download_link.href = uri;
        var ts = new Date().getTime();
        if(caption==""){
            download_link.download = ts+".csv";
        } else {
            download_link.download = caption+"-"+ts+".csv";
        }
        document.body.appendChild(download_link);
        download_link.click();
        document.body.removeChild(download_link);
    });
};

//function approveAdvanceSalaryWithPaySlip(companyId,recordId,emp_id){
//    var functionName = 'cdOne/approveAdvanceSalaryWithPaySlip';
//    $.ajax({
//        url: ''+baseUrl+'/'+functionName+'',
//        type: "GET",
//        data: {companyId:companyId,recordId:recordId,emp_id:emp_id},
//        success:function(data) {
//            location.reload();
//        }
//    });
//}

//function deleteAdvanceSalaryWithPaySlip(companyId,recordId,tableName){
//    var companyId;
//    var recordId;
//    var tableName;
//    var functionName = 'cdOne/deleteAdvanceSalaryWithPaySlip';
//
//    $.ajax({
//        url: ''+baseUrl+'/'+functionName+'',
//        type: "GET",
//        data: {companyId:companyId,recordId:recordId,tableName:tableName},
//        success:function(data) {
//            location.reload();
//        }
//    });
//
//}

//function approveLoanRequest(companyId,recordId) {
//
//    var functionName = 'cdOne/approveLoanRequest';
//    $.ajax({
//        url: ''+baseUrl+'/'+functionName+'',
//        type: "GET",
//        data: {companyId:companyId,recordId:recordId},
//        success:function(data) {
//            location.reload();
//        }
//    });
//}
//
//function rejectLoanRequest(companyId,recordId) {
//
//    var functionName = 'cdOne/rejectLoanRequest';
//    $.ajax({
//        url: ''+baseUrl+'/'+functionName+'',
//        type: "GET",
//        data: {companyId:companyId,recordId:recordId},
//        success:function(data) {
//            location.reload();
//        }
//    });
//}
//
//function deleteLoanRequest(companyId,recordId)
//{
//    var companyId;
//    var recordId;
//
//    var functionName = 'cdOne/deleteLoanRequest';
//    $.ajax({
//        url: ''+baseUrl+'/'+functionName+'',
//        type: "GET",
//        data: {companyId:companyId,recordId:recordId},
//        success:function(data) {
//            location.reload();
//        }
//    });
//}

