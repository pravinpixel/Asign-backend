var Sto = function ( config ){

    var $html = $("html");
    var f = this;
    console.log("Sto grn script", f)
    f.config = config;
    f.page = $("#main_content");
    f.productStoPopup = $("#product_sto_popup");
    f.productStoPopupLabel = $('#product_sto_popup_label');
    f.productStoPopupAvailableButton = $('.product-sto-popup-available-btn');
    f.productStoPopupAddButton = $('.product-sto-popup-add-btn');
    f.productStoPopupSaveButton = $('.product-sto-popup-save-btn');
    f.createStoButton = $('#create_sto_btn')
    f.table = $("#dynamic-sto-order")
    f.createStoForm = $('#create_sto_form');
    f.otherReasonText = $('#other_reason_text');
    f.trackingIdEditSto = $('#tracking_id_edit_sto');
    f.stoPackOrderedTable = $('#dynamic-sto-pack-ordered');
    f.productScanCode = $('#ordered_product_code');
    f.toasterConfig = {
        timeOut: 5000
        //types warning/success/error
    }
    f.otherReasonText.hide();
    f.productStoPopupSaveButton.hide();
    f.productScanCode.focus();

    // Status from the page
    if (typeof orderStatus !== 'undefined' && orderStatus === "Packed"){
        f.trackingIdEditSto.prop('disabled', false);
    }else{
        f.trackingIdEditSto.prop('disabled', true);
    }

    //Edit model data from page api
    if (typeof stoOrderedData.stock_transfer_order_products !== 'undefined'){
        var stoOrderDetail = stoOrderedData;
    }else{
        var stoOrderDetail = {}
    }
    
    // Get the CSRF token value from the meta tag
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var dataArray = [];
    var tableformatdata = {}
    var url = "";
    var location = "";
    var autoOrderNo = "";

    if (dataArray.length == 0 ){
        f.createStoButton.prop('disabled', true);
    }
  
     //Function

        //Toaster function

        f.toasterHandler = function (type, msg){
            toastr[type](msg, f.toasterConfig);
        }

        //Scan STO PACK quantity save

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
                    f.productScanCode.val('');
                    window.location.reload();
                }
            });

            http.fail( function ( xhr ) {
                if ( xhr.responseJSON && xhr.responseJSON.message == "Error" ) {

                    let errors = xhr.responseJSON.error;

                    // Display errors in the DOM
                    if( errors.product_code ){
                        // $( '#product_code_error' ).html( errors.product_code[0] );
                        // f.toasterHandler( "error", "Please scan the product label to ensure its valid" );
                        f.toasterHandler( "error", errors.product_code[0] );

                    }
                    if( errors.product_qty ){
                        f.toasterHandler( "error", errors.product_qty[0] )
                    }
                    f.productScanCode.val('');
                }
            });

            http.always(function () {

            });
        }

        // Onsuccess scan

        function onScanSuccess(decodedText, decodedResult) {
            let formatData = {}
            // Handle on success condition with the decoded text or result.
            console.log(`Scan result: ${decodedText}`, decodedResult);
            if( decodedText ){
                formatData.sto_id = stoId;
                formatData.product_code = decodedText;
                formatData.product_qty = 1;
                formatData.category = "package";
                formatData.sto_product_id = stoProductId;
                $.proxy( f.scanSubmitHandler( formatData ) )
                $('#html5-qrcode-button-camera-stop').trigger("click");
            }

        }

        // Pushing data to Ordered STO Table from Edit modal

        f.stoOrderedTableHandler = function (newData){
            
            stoOrderDetail.stock_transfer_order_products = stoOrderDetail.stock_transfer_order_products.map((arrayProduct, index) => {
               
                if (arrayProduct.product_id == parseInt(newData.product_id)){
                    return {...arrayProduct, quantity: parseInt(newData.quantity)};
                }else{
                    return arrayProduct;
                }
            });

            dataArray = stoOrderDetail.stock_transfer_order_products;

            let newRowHtml = '';
            dataArray.forEach((element, index) => {
                newRowHtml += '<tr>';
                newRowHtml += '<td>' + (index + 1) + '</td>';
                newRowHtml += '<td>' + element['product_name'] + '</td>';
                newRowHtml += '<td style="display: none;">' + element['product_id'] + '</td>';
                newRowHtml += '<td>' + element['quantity'] + '</td>';
                newRowHtml += '<td>' + element['scanned_req_quantity'] + '</td>';
                newRowHtml +=
                    '<td><button type="button" class="btn edit-sto-pack-modal-btn edit-sto-pack-modal-btn" data-bs-toggle="modal" data-bs-target="#product_sto_popup">Edit</button></td>';
                newRowHtml += '</tr>';
            });

            f.stoPackOrderedTable.find('tbody').empty();
            f.stoPackOrderedTable.append(newRowHtml);
            $('#product_sto_modal')[0].reset();
            $('.select2Box').select2('destroy').select2();
            $('.select2Box').select2({
                minimumResultsForSearch: -1
            });
            $('#product_sto_popup').modal('hide');
            f.createStoButton.prop('disabled', false);
        }

        // Arrange EDIT STO ORDERED data product model

        f.arrangeStoOrderedPopupDataHandler = function ( data ){

            // let index = dataArray.length;
            const split = data[1].value.split(',');
            let formatData = {
                // "index": index + 1,
                "product_name": split[0],
                "product_id": parseInt(split[1]),
                "quantity": data[2].value,
                // "stock_source": location ?? ""
            }
            tableformatdata = formatData;

            $.proxy(f.stoOrderedTableHandler(formatData));

        }

        // Arrange data from Add and Edit Product Model

        f.arrangeDataHandler = function ( data ){

            let index = dataArray.length;
            const split = data[1].value.split(',');
            let stock_source_value = $("#location_id").val();
            let formatData = {
                "index": index + 1,
                "product_name": split[0],
                "product_id": parseInt(split[1]),
                "quantity": data[2].value,
                "stock_source": stock_source_value ?? ""
            }
            tableformatdata = formatData;

            // When url is not exists push data to table from Edit modal
            if (url){
                $.proxy(f.productSubmitHandler(formatData));
            }
            // else{
            //     // Fill table data from edit popup
            //     $.proxy(f.stoOrderedTableHandler(formatData));
            // }

        }

        
        //Pushing dynamic data to create purchase order table

        f.tableHandler = function ( data ){
            let newRowHtml = '';
            if (data.length == 0){
                newRowHtml += '<tr>';
                newRowHtml += '<td colspan="4" class="txt-center empty-msg">Add Products</td>';
                newRowHtml += '</tr>';
            }else{
                data.forEach( ( element, index ) => {
                    newRowHtml += '<tr>';
                    newRowHtml += '<td>' + (index + 1) + '</td>';
                    newRowHtml += '<td>' + element['product_name'] + '</td>';
                    newRowHtml += '<td style="display: none;">' + element['product_id'] + '</td>';
                    newRowHtml += '<td>' + element['quantity'] + '</td>';
                    newRowHtml +=
                        '<td><button type="button" class="btn edit-sto-pack-modal-btn edit-sto-product-btn edit-sto-modal-btn" data-bs-toggle="modal" data-bs-target="#product_sto_popup">Edit</button></td>';
                    newRowHtml += '</tr>';
                });
            }
            f.table.find('tbody').empty();
            f.table.append(newRowHtml);
            // f.productPop.modal('hide');
            if (dataArray.length === 2) {
                $('#add_sto_modal_btn').prop('disabled', true);
            } else {
                $('#add_sto_modal_btn').prop('disabled', false);
    
            }
            f.createStoButton.prop( 'disabled', false );
        }

        //Product Add or edit data validation

        f.productSubmitHandler = function( formData ){
            var http = $.ajax({
                url: url,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
                },
                contentType: 'application/json',
                data: JSON.stringify( formData )
            });

            http.done( function ( xhr ) {

                // Success response from Check avaialbility
                if( xhr && xhr.data.avl_data_available == true ){
                    if( xhr.message ){
                        $('#available_qty_error').html('<img src="' + successIconUrl + '" style="position: relative; top: 0px; margin-right: 8px;" />'+xhr.message);
                    }
                    f.productStoPopupAddButton.show();
                    f.productStoPopupAvailableButton.hide();
                    f.productStoPopupSaveButton.hide();
                }

                // Success response from validate Add product
                if( xhr && xhr.data.add_data_available == true ){

                    $('#product_sto_modal')[0].reset();
                    $('.select2Box').select2('destroy').select2();
                    $('.select2Box').select2({
                        minimumResultsForSearch: -1
                    });
                    f.productStoPopup.modal('hide');

                    if (typeof stoOrderedData.stock_transfer_order_products == 'undefined') {
                        // Normal flow
                         //Table data push
                         let existingData = dataArray?.find( item => item.product_id === tableformatdata?.product_id );

                         if( !existingData ){
                             dataArray.push( tableformatdata );
                         }
                         else{
                             let existingDataIndex = dataArray.findIndex(item => item.product_id === tableformatdata?.product_id);
                             dataArray[existingDataIndex] = tableformatdata;
                         }
                         $.proxy(f.tableHandler(dataArray))
                       
                    }else{
                         // Edit flow
                         $.proxy(f.stoOrderedTableHandler(tableformatdata))
                    }
                   
                }
            });

            http.fail( function ( xhr ) {
                if ( xhr.responseJSON && xhr.responseJSON.message == "Error" ) {
                    $.each(xhr.responseJSON.error, function (field_name, error) {
                        if (field_name == "stock_source") {
                            f.toasterHandler("error", error);
                        } else if (field_name == "available_qty") {
                            $('#available_qty_error').html('<img src="' + warningIconUrl + '" style="position: relative; top: 0px; margin-right: 8px;" />' + error[0]);
                        } else {
                            $('#' + field_name + '_error').text(error[0]);
                        }
                    });
                    
                    f.productStoPopupAddButton.hide();
                    f.productStoPopupAvailableButton.show();
                    f.productStoPopupSaveButton.hide();

                }
            });

            http.always(function () {

            });
        }

        // Stock order data validation

        f.validateHandler = function( data ){

            var http = $.ajax({
                url: config.links.stoSave,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
                },
                contentType: 'application/json',
                data: JSON.stringify( data )
            });

            http.done( function ( xhr ) {
                if ( xhr?.success ) {
                    f.toasterHandler("success", xhr?.message);
                    window.location.href = base_url + "/stock-transfer-orders/summary/"+xhr?.data?.id;
                } else {
                    // HTTP status code is not OK (e.g., 404, 500, etc.)
                    f.toasterHandler("error", "Unexpected HTTP status code: " + xhr.status);
                }
            });

            http.fail( function ( xhr ) {

                if ( xhr.responseJSON && xhr.responseJSON.message == "Error" ) {
                    $.each(xhr.responseJSON.error, function (field_name, error) {
                        if (field_name == "auto_no_expired"){
                            autoOrderNo = error[0].slice(error[0].lastIndexOf(' ') + 1);
                            $('.order-no-content').text(error[0]);
                            $('#auto_no_popup').modal('show');
                        }
                        $('#' + field_name + '_error').text(error[0]);
                    });
                }

            });

            http.always(function () {

            });
        }

        $html.on("submit", "#product_sto_modal", function(e) {
            e.preventDefault();
            var fData = $(this).serializeArray();
            $.proxy(f.arrangeDataHandler(fData));

        });


        // Create Sto form submit

        $html.on("submit", "#create_sto_form", function( e ){
            e.preventDefault();
            var poData = $(this).serializeArray();
            let formData = {}
            $.each( poData, function( ind, field ) {
                formData[field.name] =  field.value;
            });
            // const manu_split = formData.manufacturer.split( ',' );
            // const loc_split = formData.location.split( ',' );
            // formData.manufacturer_name = manu_split[0];
            // formData.manufacturer_id = manu_split[1];
            // formData.location_name = loc_split[0];
            // formData.location_id = loc_split[1];
            formData.product_details = dataArray;
            createData = formData;
            $.proxy( f.validateHandler(createData) );
        });

        // Change STO Packed to Transit

        $html.on( 'submit', '#sto_transit_save_form', function(e){
            e.preventDefault();
            var stoData = $(this).serializeArray();
            let formData = {}
            $.each( stoData, function( ind, field ) {
                formData[field.name] =  field.value;
            });
            formData.product_details = stoDetails.stock_transfer_order_products;
            $.proxy( f.saveStoTransitHandler(formData) );
        })

        // Reset STO PACK scan quantity

        f.resetStoPackHandler = function( formData ){

            var http = $.ajax({

                url: f.config.links.stoPackReset + '?product_id=' + productId + '&sto_product_id=' + stoProductId,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
                },
                contentType: 'application/json',
                // data: JSON.stringify( formData )
            });

            http.done( function ( xhr ) {

                if( xhr && xhr.message == "Success" ){
                    f.toasterHandler("success", "Reset Stack Pack Quantity Successfully" );
                    window.location.reload();
                }

            });

            http.fail( function ( xhr ) {
                console.log("error", xhr)
            });

            http.always(function () {

            });
        }

        // // Save STO ordered

        // f.saveStoOrderedHandler = function( data ){

        //     var http = $.ajax({
        //         url: config.links.stoEditPack,
        //         type: "POST",
        //         headers: {
        //             'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        //         },
        //         contentType: 'application/json',
        //         data: JSON.stringify( data )
        //     });

        //     http.done( function ( xhr ) {
        //         if ( xhr?.success ) {
        //             f.toasterHandler("success", xhr?.message);
        //             window.location.href = base_url + "/stock-transfer-orders";
        //         } else {
        //             // HTTP status code is not OK (e.g., 404, 500, etc.)
        //             f.toasterHandler("error", "Unexpected HTTP status code: " + xhr.status);
        //         }
        //     });

        //     http.fail( function ( xhr ) {

        //         if ( xhr.responseJSON && xhr.responseJSON.message == "Error" ) {
        //             $.each(xhr.responseJSON.error, function (field_name, error) {
        //                 $('#' + field_name + '_error').text(error[0]);
        //             });
        //         }

        //     });

        //     http.always(function () {

        //     });
        // }


        //Save STO Packed

        f.saveStoPackedSave = function( data ){

            var http = $.ajax({
                url: config.links.stoPackOrder,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
                },
                contentType: 'application/json',
                data: JSON.stringify( data )
            });

            http.done( function ( xhr ) {
                if ( xhr?.success ) {
                    f.toasterHandler("success", xhr?.message);
                    window.location.href = base_url + "/stock-transfer-orders";
                } else {
                    // HTTP status code is not OK (e.g., 404, 500, etc.)
                    f.toasterHandler("error", "Unexpected HTTP status code: " + xhr.status);
                }
            });

            http.fail( function ( xhr ) {

                if ( xhr.responseJSON && xhr.responseJSON.message == "Error" ) {
                    $.each(xhr.responseJSON.error, function (field_name, error) {
                        $('#' + field_name + '_error').text(error[0]);
                    });
                }

            });

            http.always(function () {

            });
        }

        f.checkFields = function () {
            var stockSource = $('#location_id').val();
            var stockDestination = $('#destination_id').val();
            var transferReason = $('#transfer_reason_list').val();
            var createdDate = $('#created_date').val();

            // Enable button if all fields are filled, otherwise disable
            if (stockSource && stockDestination && transferReason && createdDate) {
                $('#add_sto_modal_btn').prop('disabled', false);
            } else {
                $('#add_sto_modal_btn').prop('disabled', true);
            }
        }
        // Save STO Transit order

        f.saveStoTransitHandler = function( data ){

            var http = $.ajax({
                url: config.links.stoTransitSave,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
                },
                contentType: 'application/json',
                data: JSON.stringify( data )
            });

            http.done( function ( xhr ) {
                if ( xhr?.success ) {
                    f.toasterHandler("success", xhr?.message);
                    window.location.href = base_url + "/stock-transfer-orders";
                } else {
                    // HTTP status code is not OK (e.g., 404, 500, etc.)
                    f.toasterHandler("error", "Unexpected HTTP status code: " + xhr.status);
                }
            });

            http.fail( function ( xhr ) {

                if ( xhr.responseJSON && xhr.responseJSON.message == "Error" ) {
                    $.each(xhr.responseJSON.error, function (field_name, error) {
                        $('#' + field_name + '_error').text(error[0]);
                    });
                }

            });

            http.always(function () {

            });
        }

        // Edit STO Ordered API

        f.stoOrderedSaveProductApi = function (formData){
            var http = $.ajax({
                url: config.links.stoOrderedProductEdit,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
                },
                contentType: 'application/json',
                data: JSON.stringify( formData )
            });

            http.done( function ( xhr ) {
                if (xhr && xhr.success){
                    window.location.href = base_url+"/stock-transfer-orders/pack/"+stoId+"/Ordered";
                }
            });

            http.fail( function ( xhr ) {
                $.each(xhr.responseJSON.error, function (field_name, error) {
                    f.toasterHandler( "error", error[0] );
                });
            });

            http.always(function () {

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
                        window.location.href = base_url + "/goods-received-notes/create-grn/"+stoId+"/"+ xhr.data.id + "/sto";

                    }
                });
                }
            });

            http.fail( function ( xhr ) {
              console.log("xhr", xhr);
            });

            http.always(function () {

            });
        }


        // Events

        // Add modal dynamic title, product, quantity

        f.page.on( 'click', '#add_sto_modal_btn', function( e ){
            e.preventDefault();
            $('#product_sto_modal')[0].reset();
            $('.select2Box').select2('destroy').select2();
            $('.select2Box').select2({
                minimumResultsForSearch: -1
            });
            // Initially all list value enabled
            let select2Instance = $('#select_sto_popup_product').data('select2');
            select2Instance.$element.find('option').prop('disabled',false);

            // Dynamic add data setting
            f.productStoPopupLabel.text("Add Product");
            f.productStoPopupAvailableButton.prop( 'disabled', true );
            f.productStoPopupAvailableButton.show();
            f.productStoPopupAddButton.hide();
            f.productStoPopupSaveButton.hide();
            $('#select_sto_popup_product').val('').trigger('change');
            $('#quantity_name_error').html('');
            $('#available_qty_error').html('');

            //Disable already exist products in select
            dataArray.filter((obj) => {
                if ('product_name' in obj && 'product_id' in obj) {
                    if (obj.product_name !== null && obj.product_id !== null) {
                        select2Instance.$element.find('option[value="' + obj.product_name + ',' + obj.product_id + '"]').prop('disabled', true);
                    }
                }
            });
        });

         // Edit STO PACK modal dynamic title, product, quantity

         f.page.on( 'click', '.edit-sto-pack-modal-btn', function( e ){
            e.preventDefault();
            // $('.product-sto-popup-available-btn').show();
            f.productStoPopupAddButton.prop('disabled', false);
            f.productStoPopupAvailableButton.hide();
            f.productStoPopupAddButton.show();
            f.productStoPopupAddButton.text('Save');
            // Dynamic record get from Create po table
            let row = $(this).closest('tr');
            let id = row.find('td:first').text();
            let productName = row.find('td').eq(1).text();
            let productId = row.find('td').eq(2).text();
            let quantity = row.find('td').eq(3).text();

            // Initially all list value enabled
            let select2Instance = $('#select_sto_popup_product').data('select2');

            select2Instance.$element.find('option').prop('disabled', true);

            //disable edit modal load data
            select2Instance.$element.find('option[value="' + productName + ',' + productId + '"]').prop('disabled', false);

            // Dynamic edit data setting
            $('#product_sto_popup_label').text("Edit Product");
            $('#select_sto_popup_product').val(productName+","+productId).trigger('change');
            $('#qty').val(quantity);
            $('#available_qty_error').html("");
            f.productStoPopupSaveButton.hide();

        });
        
        // When data enter after that button would be enable

        f.productStoPopup.on('change keyup paste', function(e) {
            if($('#select_sto_popup_product').find('option:selected').length > 0 && ($('#qty').val().length > 0)){
                f.productStoPopupAvailableButton.prop( 'disabled', false );
                f.productStoPopupAddButton.prop('disabled', false);
            }else{
                f.productStoPopupAvailableButton.prop( 'disabled', true );
                f.productStoPopupAddButton.prop('disabled', true);

            }
        });


        $('.cancel-sto-btn').on('click', function(){
            f.productStoPopup.modal('hide');
        });


        f.page.on( 'click', '#sto_available_btn', function( e ){
            e.preventDefault();
            url = f.config.links.productCheckAvailable
            $('#product_sto_modal').submit();
        })

        f.page.on( 'click', '.product-sto-ordered-save-btn', function( e ){
            e.preventDefault();
            // url = f.config.links.productCheckAvailable
            $('#product_sto_modal').submit();
        })

        f.page.on( 'click', '#sto_add_btn', function( e ){
            e.preventDefault();
            url = f.config.links.productValidate
            $('#product_sto_modal').submit();
        })

        f.page.on( 'click', '#sto_save_btn', function( e ){
            e.preventDefault();
            $('#product_sto_modal').submit();
        })


        f.page.on( 'click', '#create_sto_btn', function( e ){
            e.preventDefault();
            f.createStoForm.submit();
        })

        f.page.on( 'click', '#order_no_save', function( e ){
            e.preventDefault();
            $('#auto_sto_no').val(autoOrderNo);
            f.createStoForm.submit();
        })

        f.page.on( 'change', '#transfer_reason_list', function(e){
            let labelText = $(this).find('option:selected').text();
            if( labelText == "Others" || labelText == "others" ){
                f.otherReasonText.show();
            }else{
                f.otherReasonText.hide();
            }
        });

        f.page.on( 'change', '#location_id', function(e){
            let newRowHtml = '';
            let select2Instance = $('#destination_id').data('select2');
            select2Instance.$element.find('option').prop('disabled', false);
            location = $(this).val();
            select2Instance.$element.find('option[value="'+location+'"]').prop('disabled', true);
            f.table.find('tbody').empty();
            newRowHtml += '<tr>';
            newRowHtml += '<td colspan="4" class="txt-center empty-msg">Add Products</td>';
            newRowHtml += '</tr>';
            f.table.append(newRowHtml);
            
        });

        f.page.on( 'change', '#destination_id', function(e){
            let select2Instance = $('#location_id').data('select2');
            select2Instance.$element.find('option').prop('disabled', false);
            location = $(this).val();
            select2Instance.$element.find('option[value="'+location+'"]').prop('disabled', true);
        });

        f.page.on('keydown', '#ordered_product_code', function(e){
            // e.preventDefault();
            let formatData = {}
            var scanValue = $(this).val();

            if (e.keyCode === 13 && scanValue !== ''){

                formatData.sto_id = stoId;
                formatData.product_code = scanValue;
                // formatData.product_qty = 1;
                formatData.sto_product_id = stoProductId;
                // formatData.category = "packet"
                formatData.product_id = productId;
                $.proxy( f.scanSubmitHandler( formatData ) )
            }
        });

        f.page.on( 'click', '#scan_sto_pack_reset', function(e){
            $.proxy( f.resetStoPackHandler() );
        })

        f.page.on( 'click', '#pack_order_save_btn', function(e){

            let formData = {};
            formData.sto_id = sto_id;
            formData.product_details = stoDetails.stock_transfer_order_products;
            $.proxy( f.saveStoPackedSave(formData) );
        })

        f.page.on( 'click', '#sto_transit_save_btn', function(e){
            $('#sto_transit_save_form').submit();
        })


        f.page.on('click', '#sto_ordered_save_button', function(e){
            e.preventDefault();
            let formData = {};
            formData.sto_id = stoId;
            if ( dataArray.length === 0){

                // Check the availablity of oredered sto 
                formData.product_details= stoOrderedData.stock_transfer_order_products;
                $.proxy(f.stoOrderedSaveProductApi(formData));
            } else {
                formData.product_details = dataArray;
                $.proxy(f.stoOrderedSaveProductApi(formData));
            }
        });

        f.page.on( 'click', '.create_sto_grn_id', function(e){
            e.preventDefault();
            let formatData = {
                "order_id": stoId ?? 0,
                "type": "sto"
            }
            $.proxy( f.grnIdHandler(formatData) )
        })

        $('.sto-cancel-btn').on('click', function(){
            f.productStoPopup.modal('hide');
        });

        // STO print pdf click
        $('.sto_print_btn').on('click', function(e){
            e.preventDefault();
            var url = $(this).attr('href');
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
        // $('#qty').on('change keyup paste', function(e) {
        //     if($('#qty').val() <= 0){
        //         $('#quantity_name_error').text('Quantity should be greater than 0');
        //     }else{
        //         $('#quantity_name_error').text('');
        //     }
        // });

        // $('#location_id, #destination_id, #transfer_reason_list, #created_date').on('change', function() {
        //     $.proxy(f.checkFields());
        // });

        // var html5QrcodeScanner = new Html5QrcodeScanner(
        //     "reader", { fps: 10, qrbox: 250 });
        // html5QrcodeScanner.render(onScanSuccess)
        // f.checkFields()
}
