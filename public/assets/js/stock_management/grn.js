var Grn = function (config) {

    var $html = $("html");
    var f = this;
    console.log("Grn script", f)
    f.config = config;
    f.page = $("#main_content");
    f.grnListTable = $('#grn_list_table');
    f.grnCreateTable = $('#grn_create_table');
    f.scanCode = $('#scan_code');
    f.toasterConfig = {
        timeOut: 5000
        //types warning/success/error
    }
    f.scanCode.focus();
    // Get the CSRF token value from the meta tag
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var selectTransporterName = sessionStorage.getItem('selected_transporter');
    var stoGrnTransporterName = sessionStorage.getItem('sto_grn_transporter');

    if (selectTransporterName){
        $('#transporter_po_grn').val(selectTransporterName).trigger('change');
    }

    if (stoGrnTransporterName){
        $('#sto_grn_transporter').val(stoGrnTransporterName).trigger('change');
    }

    f.listSearchHandler = function ( searchValue  ){

        var http = $.ajax({
            url: f.config.links.list + '?search=' + searchValue,
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers if needed
            },
            data: {
                search: ( searchValue ) ? searchValue : ""  // Add your search parameter
                // Add more parameters if needed, e.g., param2: value2
            }
        });

        http.done(function (data) {
            if( data?.success ){
                $.proxy( f.grnListTableHandler( data?.data ) )
                // f.purchaseListTable.append(newRowHtml);
            }
        });

        http.fail(function () {
            // Handle errors
            console.log("Error: Something went wrong!");
        });
    }

    // Create grn Id handler for grn page
    f.grnIdHandler = function( formData ){

        var http = $.ajax({
            url: f.config.links.grnid,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
            },
            contentType: 'application/json',
            data: JSON.stringify( formData )
        });

        http.done( function ( xhr ) {
            if( xhr && xhr.message == "Success" ){
               http.done(function(xhr) {
                if (xhr && xhr.message == "Success") {
                    //This line for grn direct create
                    window.location.href = base_url + "/goods-received-notes/create-grn/0/" + xhr.data.id;

                }
            });
            }
        });

        http.fail( function ( xhr ) {
            // if ( xhr.responseJSON && xhr.responseJSON.message == "Error" ) {
            //     var errors = xhr.responseJSON.error;

            //     // Display errors in the DOM
            //     if ( errors.product_id ) {
            //         $( '#product_name_error' ).html( errors.product_id[0] );
            //     }
            //     if( errors.quantity ){
            //         $( '#quantity_name_error' ).html( errors.quantity[0] );
            //     }

            // }
        });

        http.always(function () {

        });
    }
    // Event

    f.page.on( 'click', '#scan_grn_reset', function(e){
        $.proxy( f.resetHandler() );
    })

    f.page.on( 'change', '#grn_search', function( e ){
        e.preventDefault();
        var searchValue = $(this).val();
        $.proxy( f.listSearchHandler( searchValue ) );
    });

    f.page.on( 'click', '#grn_save_button', function(e){
        $("#grn_save_form").submit();
    })

    f.page.on( 'click', '#grn_no_save', function(e){
        $("#grn_save_form").submit();
    })

    f.page.on( 'click', '.create_grn_id', function(e){
        e.preventDefault();
        let formatData = {
            "order_id": purchaseOrderId ?? 0,
            // "type": "po"
        }
        $.proxy( f.grnIdHandler(formatData) )
    })


    f.page.on( 'keydown', '#scan_code', function( e ){
    
        let formatData = {}
        var scanValue = $(this).val();
        if (e.keyCode === 13 && scanValue !== ''){
            formatData.grn_id = grnId;
            formatData.product_code = scanValue;
            formatData.product_id = productId;
            formatData.order_product_id = orderProductId;
            formatData.type = requestType;
            console.log(formatData, "formatDatga");
            $.proxy( f.scanSubmitHandler( formatData ) )
        }
    });

    //Fuction

    f.toasterHandler = function (type, msg){
        toastr[type](msg, f.toasterConfig);
    }

    function onScanSuccess(decodedText, decodedResult) {
        let formatData = {}
        // Handle on success condition with the decoded text or result.
        console.log(`Scan result: ${decodedText}`, decodedResult);
        if( decodedText ){
            formatData.grn_no = grnId;
            formatData.product_code = decodedText;
            formatData.product_qty = 1;
            formatData.po_product_id = orderProductId;
            $.proxy( f.scanSubmitHandler( formatData ) )
            $('#html5-qrcode-button-camera-stop').trigger("click");
        }

    }

    function onScanError(errorMessage) {
        // handle on error condition, with error message
        console.log("Error", errorMessage);
    }

    // Reset the scan quantity

    f.resetHandler = function( formData ){

        var http = $.ajax({

            url: f.config.links.reset + '?order_product_id=' + (orderProductId ?? "") + '&type=' + (requestType ?? "") + '&grn_id=' + (grnId ?? ""),
            type: "DELETE",
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
            },
            contentType: 'application/json',
            // data: JSON.stringify( formData )
        });

        http.done( function ( xhr ) {

            if( xhr && xhr.message == "Success" ){
                f.toasterHandler("success", "Reset quantity Successfully" );
                window.location.reload();
            }

        });

        http.fail( function ( xhr ) {
            console.log("error", xhr)
        });

        http.always(function () {

        });
    }

    f.grnSaveHandler = function( data ){

        var http = $.ajax({
            url: f.config.links.grnsave,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
            },
            contentType: 'application/json',
            data: JSON.stringify( data )
        });

        http.done( function ( xhr ) {
            console.log(xhr, "xhr");
            if ( xhr && xhr.message == "Success" ) {
                if (requestType === "sto"){
                    window.location.href = base_url + "/goods-received-notes/summary/"+grnId+"/sto";
                }else{
                    window.location.href = base_url + "/goods-received-notes/summary/"+grnId+"/po";
                }
            }
        });

        http.fail( function ( xhr ) {

            if ( xhr.responseJSON && xhr.responseJSON.message == "Error" ) {
                let errors = xhr.responseJSON.error;
                // Display errors in the DOM
                $.each(xhr.responseJSON.error, function (field_name, error) {
                    if (field_name == "auto_no_expired"){
                        autoOrderNo = error[0].slice(error[0].lastIndexOf(' ') + 1);
                        $('.order-no-content').text(error[0]);
                        $('#grn_auto_no_popup').modal('show');
                    }
                    $('#' + field_name + '_error').text(error[0]);
                });
            }

        });

        http.always(function () {

        });
    }


     //Scan quantity save

     f.scanSubmitHandler = function( formData ){

        var http = $.ajax({
            url: f.config.links.scan,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
            },
            contentType: 'application/json',
            data: JSON.stringify( formData )
        });

        http.done( function ( xhr ) {

            if( xhr && xhr.message == "Success" ){
                f.scanCode.val('');
                window.location.reload();
            }
        });

        http.fail( function ( xhr ) {
             if ( xhr.responseJSON && xhr.responseJSON.message == "Error" ) {

                let errors = xhr.responseJSON.error;
                if(errors.product_code == 'Label contains information that already exists'){
                    f.toasterHandler( "error", "Label contains information that already exists" );
                }
                // Display errors in the DOM
                else if( errors.product_code ){
                    // $( '#product_code_error' ).html( errors.product_code[0] );
                    f.toasterHandler( "error", "Please scan the product label to ensure its valid" );
                }
                if( errors.product_qty ){
                    f.toasterHandler( "error", errors.product_qty[0] )
                }
                f.scanCode.val('');
            }
        });

        http.always(function () {

        });
    }

    //Show PO Summary Table data push
    f.grnListTableHandler =  function( data ){

        let newRowHtml = '';
        if( data ){
            data.forEach( ( grn ) => {

                newRowHtml += '<tr>';
                newRowHtml += '<td>' + grn['grn_no'] + '</td>';
                newRowHtml += '<td>' + grn['purchase_order_no'] + '</td>';
                newRowHtml += '<td>' + grn['manufacturers_name'] + '</td>';
                newRowHtml += '<td>' + grn['branch_name'] + '</td>';
                newRowHtml += '<td>' + grn['created_on'] + '</td>';
                newRowHtml += '</tr>';
            });
            f.grnListTable.find('tbody').empty();
            f.grnListTable.append( newRowHtml );
        }else{
            f.grnListTable.find('tbody').empty();
        }
    }


    f.grnCreateTableHandler =  function( data, no_type){
        let newRowHtml = '';
        if( data ){
            let orderProductDetail = '';
            if (no_type === 'sto') {
                $('.po_grn_sender_dropdown').hide();
                $('.sto_grn_sender_dropdown').show();
                $('#grn_loc').val(data.stock_destination_id).trigger('change');
                $('#order_date').val(data.created_date);
                $('.sto_sender').val(data.stock_source_id).trigger('change');
                orderProductDetail = data.stock_transfer_order_products;
            }else{
                $('.po_grn_sender_dropdown').show();
                $('.sto_grn_sender_dropdown').hide();
                $('#grn_loc').val(data.delivery_location).trigger('change');
                $('#order_date').val(data.order_date);
                $('.po_sender').val(data.manufacturer_name).trigger('change');
                orderProductDetail = data.purchase_order_products;

            }

            orderProductDetail.forEach((grnPoProduct, index) => {

                newRowHtml += '<tr>';
                newRowHtml += '<td>' + ( index +1 ) + '</td>';
                newRowHtml += '<td>' + grnPoProduct['product_name'] + '</td>';
                newRowHtml += '<td>' + grnPoProduct['quantity'] + '</td>';
                newRowHtml += '<td>' + grnPoProduct['grn_quantity'] + '</td>';
                newRowHtml += '<td>' + grnPoProduct['grn_quantity_currently_scanned_products'] + '</td>';
                if (grnPoProduct['quantity'] === grnPoProduct['grn_quantity']){
                    newRowHtml += '<td><a href="'+ base_url +'/goods-received-notes/scan/'+ grnPoProduct['product_id']+ '/' + grnPoProduct['id']+ '/'  + mainGrnId + '/'  + no_type+'" class="btn apply-btn apply-btn-md" style="pointer-events: none; opacity: 0.5;cursor: not-allowed;">Scan</a></td>';
                } else {
                    newRowHtml += '<td><a href="'+ base_url +'/goods-received-notes/scan/'+ grnPoProduct['product_id']+ '/' + grnPoProduct['id']+ '/'  + mainGrnId + '/'  + no_type+'" class="btn apply-btn apply-btn-md">Scan</a></td>';
                }
                newRowHtml += '</tr>';
            });

            orderProductDetail.forEach( ( grnPoProduct, index ) => {
                if( grnPoProduct['grn_quantity_currently_scanned_productsy'] > 0 ){
                    return $( '#grn_save_button' ).prop( 'disabled', false );

                }
                if( index === data.length-1 && grnPoProduct['grn_quantity_currently_scanned_products'] == 0 ){

                    return $( '#grn_save_button' ).prop( 'disabled', true );
                }
            });
            f.grnCreateTable.find('tbody').empty();
            f.grnCreateTable.append( newRowHtml );
        }else{
            f.grnCreateTable.find('tbody').empty();
        }
    }

    f.grnCreateTableGetHandler = function( poNo, no_type ){

        let data = {
            'po_id': poNo,
            'type': no_type,
            'grn_id' : $('input[name="grn_id"]').val()
        }
        var http = $.ajax({
            url: f.config.links.poProduct,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
            },
            contentType: 'application/json',
            data: JSON.stringify( data )
        });

        http.done( function ( xhr ) {
            if ( xhr && xhr.message == "Success" ) {
                f.grnCreateTableHandler( xhr.data, no_type );
            }
        });

        http.fail( function ( xhr ) {
            console.log("xhrERror", xhr.responseJSON.error)
        });

        http.always(function () {

        });
    }

    // Event

    f.page.on( 'change', '#po_no_dropdown', function(e){
        let poNo = $(this).val();
        orderNo = poNo;
        var selected = $(this).find('option:selected');
        var type = selected.data('type');
        $.proxy( f.grnCreateTableGetHandler(poNo, type) )
    });
    // Save Grn form

    $html.on("submit", "#grn_save_form", function(e) {
        e.preventDefault();
        var data = $(this).serializeArray();
        let formData = {}
        $.each( data, function( ind, field ) {
            formData[field.name] =  field.value;
        });
        // console.log(requestType, grnId)
        if (requestType == "sto"){
            formData.product_details = orderDetails.stock_transfer_order_products;
            formData.type = "sto"
            formData.order_no = orderId
        } else if (requestType == "po"){
            formData.product_details = orderDetails.purchase_order_products;
            formData.type = "po"
            formData.order_no = orderId
        }
        $.proxy( f.grnSaveHandler( formData ) );

    });

    f.page.on( 'change', '#transporter_po_grn', function( e ){
        e.preventDefault();
        transporterValue = $(this).val();
        sessionStorage.setItem('selected_transporter', transporterValue);

    });

    f.page.on( 'change', '#sto_grn_transporter', function( e ){
        e.preventDefault();
        transporterValue = $(this).val();
        sessionStorage.setItem('sto_grn_transporter', transporterValue);

    });

    // GRN print pdf click

     $('.grn_print_btn').on('click', function(e){
        e.preventDefault();

        var url = printUrl;
        // Open the URL in a new window
        var newWindow = window.open(url, '_blank');
        if (newWindow !== null) {
            // Wait for the new window to load
            newWindow.onload = function() {
                // Trigger the print dialog
                newWindow.print();
            };
        }
    });

    // f.page.on('input', '#scan_code' ,function(e){
    //     $(this).val("");
    // });
    // var html5QrcodeScanner = new Html5QrcodeScanner(
    //     "reader", { fps: 10, qrbox: 250 });
    // html5QrcodeScanner.render(onScanSuccess)

}
