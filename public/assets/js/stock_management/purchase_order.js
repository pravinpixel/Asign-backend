var Po = function (config) {

	var $html = $("html");
    var f = this;
    console.log("Po script", f)

    f.config = config;
    f.page = $("#main_content");
    f.table = $("#dynamic-purchase-order")
    f.productPop = $('#product_popup')
    f.createPoSaveForm = $('#create_po_form')
    f.createPoButton = $('#create_po_button')
    f.purchaseCreateArrow = $('#purchase_create_arrow')
    f.purchaseListTable = $('#purchase_list_table');
    f.purchaseOrderTitle = $('#purchase_order_title span')
    f.dynamicSummaryContent = $('#dynamic_summary_content')
    f.productPopupSaveButton = $('.product-popup-save-btn');
    f.productModalForm = $('#product_modal');
    f.editModalBtn = $('.edit_modal_btn');
    f.productPopupLabel = $('#product_popup_label');
    // f.purchaseOrderNo = $('#purchase_order_no');
    f.toasterConfig = {
        timeOut: 5000
        //types warning/success/error
    }

      // Get the CSRF token value from the meta tag
      var csrfToken = $('meta[name="csrf-token"]').attr('content');
      var dataArray = [];
      var tableformatdata = {}

    //Function

    //Toaster function
    f.toasterHandler = function (type, msg){
        toastr[type](msg, f.toasterConfig);
    }

    //Pushing dynamic data to create purchase order table
    f.tableHandler = function ( data ){
        let newRowHtml = '';
        data.forEach( ( element, index ) => {
            newRowHtml += '<tr>';
            newRowHtml += '<td>' + (index + 1) + '</td>';
            newRowHtml += '<td>' + element['product_name'] + '</td>';
            newRowHtml += '<td style="display: none;">' + element['product_id'] + '</td>';
            newRowHtml += '<td>' + element['quantity'] + '</td>';
            newRowHtml += '<td><button type="button" class="btn cancel-btn edit-product-btn edit_modal_btn" data-bs-toggle="modal" data-bs-target="#product_popup">Edit</button></td>';
            newRowHtml += '</tr>';
        });
        f.table.find('tbody').empty();
        f.table.append(newRowHtml);
        // f.productPop.modal('hide');
        if (products.length === data.length){
            $('#add_modal_btn').prop('disabled', true);
        }else{
            $('#add_modal_btn').prop('disabled', false);

        }
        f.createPoButton.prop( 'disabled', false );
    }

    //Show PO Summary Table data push
    f.purchaseListTableHandler =  function( data ){

        let newRowHtml = '';
        if( data ){
            data.forEach( ( po ) => {

                newRowHtml += '<tr>';
                newRowHtml += '<td>' + po['order_date'] + '</td>';
                newRowHtml += '<td>' + po['purchase_order_no'] + '</td>';
                newRowHtml += '<td>' + po['manufacturer_name'] + '</td>';
                newRowHtml += '<td>' + po['branch_location']['location'] + '</td>';
                newRowHtml += '<td>' + po['status'] + '</td>';
                newRowHtml += '</tr>';
            });
            f.purchaseListTable.find('tbody').empty();
            f.purchaseListTable.append( newRowHtml );
        }else{
            f.purchaseListTable.find('tbody').empty();
        }
    }

    //Arrange data from Add and Edit Product Model
    f.arrangeDataHandler = function ( data ){

        let index = dataArray.length + 1;
        if(data[3].value){
            index = parseInt(data[3].value);
        }else{
            index
        }
        const split = data[1].value.split(',');
        let formatData = {
            "index": index,
            "product_name": split[0],
            "product_id": parseInt(split[1]),
            "quantity": data[2].value
        }
        console.log(formatData);
        // localStorage.setItem('tableData', JSON.stringify(formatData));
        tableformatdata = formatData;

        $.proxy( f.productSubmitHandler( formatData ) )

    }

    // List PO handler
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
                $.proxy( f.purchaseListTableHandler( data?.data ) )
                // f.purchaseListTable.append(newRowHtml);
            }
        });

        http.fail(function () {
            // Handle errors
            console.log("Error: Something went wrong!");
        });
    }

    //Product Add or edit data validation
    f.productSubmitHandler = function( formData ){

        var http = $.ajax({
            url: f.config.links.product,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
            },
            contentType: 'application/json',
            data: JSON.stringify( formData )
        });

        http.done( function ( xhr ) {

            if( xhr && xhr.message == "Success" ){
                $('#product_modal')[0].reset();
                $('.select2Box').select2('destroy').select2();
                $('.select2Box').select2({
                    minimumResultsForSearch: -1
                });
                f.productPop.modal('hide');

                //Table data push
                let existingData = dataArray?.find( item => item.index === tableformatdata?.index );
                if( !existingData ){
                    dataArray.push( tableformatdata );
                }
                else{
                    let existingDataIndex = dataArray.findIndex(item => item.index === tableformatdata?.index);
                    dataArray[existingDataIndex] = tableformatdata;
                }
                $.proxy( f.tableHandler( dataArray ) )

            }
            // window.location.href = "{{ route('purchase-orders') }}";
        });

        http.fail( function ( xhr ) {
            if ( xhr.responseJSON && xhr.responseJSON.message == "Error" ) {
                var errors = xhr.responseJSON.error;

                // Display errors in the DOM
                if ( errors.product_id ) {
                    $( '#product_name_error' ).html( errors.product_id[0] );
                }
                if( errors.quantity ){
                    $( '#quantity_name_error' ).html( errors.quantity[0] );
                }

            }
        });

        http.always(function () {

        });
    }

    // Create po form submit handler

    f.submitHandler = function( formData ){

        var http = $.ajax({
            url: f.config.links.save,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
            },
            contentType: 'application/json',
            data: JSON.stringify( formData )
        });

        http.done( function ( xhr ) {

            if ( xhr?.success ) {
                f.toasterHandler("success", xhr?.message);
                window.location.href = base_url + "/purchase-orders";
            } else {
                // HTTP status code is not OK (e.g., 404, 500, etc.)
                f.toasterHandler("error", "Unexpected HTTP status code: " + xhr.status);
            }
            // window.location.href = "{{ route('purchase-orders') }}";
        });

        http.fail( function ( xhr ) {
            if ( xhr.responseJSON && xhr.responseJSON.error ) {
                var errorData = xhr.responseJSON.error;

                // Loop through each key in the 'error' object
                $.each( errorData, function ( fieldName, errorMessages ) {
                    // Loop through the array of error messages for each field
                    $.each( errorMessages, function ( index, errorMessage ) {
                        // Construct and display your error toaster message
                        f.toasterHandler( "error", errorMessage );
                    });
                });
            } else {
                // Handle other types of errors
                console.log("An unexpected error occurred");
                f.toasterHandler("error", "Something went wrong!");
            }
        });

        http.always(function () {

        });
    }

    //Grn id creation

    f.grnIdGeneratorHandler = function( formData ){

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
            if (xhr && xhr.message == "Success") {
                // if Po order id is not come form response set route po order id
               po_id =  ( xhr.data.purchase_order_id ) ? xhr.data.purchase_order_id : purchaseOrderId;
                window.location.href = base_url + "/goods-received-notes/create-grn/"+ po_id + "/" + xhr.data.id + "/po";
            }
        });

        http.fail( function ( xhr ) {
            if ( !xhr.responseJSON.success ) {
                f.toasterHandler( "error", xhr.responseJSON.message );
            }
        });

        http.always(function () {

        });
    }


    // Events

   //First click create button
    f.page.on( 'click', '#create_po_button', function( e ){
        e.preventDefault();
        f.createPoSaveForm.submit();
    });

    // //Final po save button
    // f.page.on( 'click', '#create_po_save', function(e){
    //     e.preventDefault();
    //     const manu_split = createData.manufacturer.split( ',' );
    //     const loc_split = createData.location.split( ',' );
    //     let poSave = {}
    //     poSave.manufacturer_name = manu_split[0];
    //     poSave.manufacturer_id = manu_split[1];
    //     poSave.location_name = loc_split[0];
    //     poSave.location_id = loc_split[1];
    //     poSave.order_date = createData.order_date;
    //     poSave.purchase_no =  createData.purchase_no;
    //     poSave.product_details = createData.product_details;
    //     $.proxy( f.submitHandler( poSave ) )
    // })

    // Po list search

    f.page.on( 'change', '#purchase_search', function( e ){
        e.preventDefault();
        var searchValue = $(this).val();
        $.proxy(f.listSearchHandler( searchValue ));
    });

    // Po name change to title

    f.page.on( 'change', '#purchase_order_no', function( e ){
        e.preventDefault();
        var changeValue = $(this).val();
        f.purchaseOrderTitle.text(changeValue);
    });


    f.page.on( 'click', '.creategrn_id', function(e){
        e.preventDefault();
        let formatData = {
            "order_id": purchaseOrderId ?? 0
        }
        $.proxy( f.grnIdGeneratorHandler(formatData) )
    })


    // Edit modal dynamic title, product, quantity

    f.page.on( 'click', '.edit_modal_btn', function( e ){
        e.preventDefault();
        // Dynamic record get from Create po table
        let row = $(this).closest('tr');
        let id = row.find('td:first').text();
        let productName = row.find('td').eq(1).text();
        let productId = row.find('td').eq(2).text();
        let quantity = row.find('td').eq(3).text();

        // Initially all list value enabled
        let select2Instance = $('#select_popup_product').data('select2');
        select2Instance.$element.find('option').prop('disabled',false);

        // Compare parent product list and dynamic table and matched item should disabled
        products.forEach(obj => {
            if ('name' in obj && 'id' in obj) {
              const hasMatch = dataArray.some(dataItem =>
                'product_name' in dataItem && 'product_id' in dataItem &&
                'name' in obj && 'id' in obj &&
                dataItem.product_name === obj.name && dataItem.product_id === obj.id
              );

              if (hasMatch) {
                select2Instance.$element.find('option[value="' + obj.name + ',' + obj.id + '"]').prop('disabled', true);
              }
            }
          });

          // All ready exist data should enabled
          select2Instance.$element.find('option[value="' +productName+ ','+ productId+ '"]').prop('disabled', false);

          // Dynamic edit data setting
        f.productPopupLabel.text("Edit Product");
        $('.product-popup-save-btn').text("Save");
        $('#index').val(id);
        $('#select_popup_product').val(productName+","+productId).trigger('change');
        $('#qty').val(quantity);
    });

    // Add modal dynamic title, product, quantity

    f.page.on( 'click', '#add_modal_btn', function( e ){
        $('#index').val('');
        e.preventDefault();
        f.productPopupSaveButton.prop('disabled', true); 

        // Initially all list value enabled
        let select2Instance = $('#select_popup_product').data('select2');
        select2Instance.$element.find('option').prop('disabled',false);

        // Dynamic add data setting
        f.productPopupLabel.text("Add Product");
        $('.product-popup-save-btn').text("Add");
        $('#select_popup_product').val('').trigger('change');
        $('#qty').val('');
        $('#quantity_name_error').text('');
        //Disable already exist products in select
        dataArray.filter((obj) => {
            if ('product_name' in obj && 'product_id' in obj) {
                if (obj.product_name !== null && obj.product_id !== null) {
                    select2Instance.$element.find('option[value="' + obj.product_name + ',' + obj.product_id + '"]').prop('disabled', true);
                }
            }
        });

    });



    // Product modal save data

    $html.on("submit", "#product_modal", function(e) {
        e.preventDefault();

        var fData = $(this).serializeArray();
        $.proxy(f.arrangeDataHandler(fData));

    });

    // First Po form validation

    $html.on("submit", "#create_po_form", function(e) {
        e.preventDefault();
        var poData = $(this).serializeArray();
        let formData = {}
        $.each( poData, function( ind, field ) {
            formData[field.name] =  field.value;
        });

        formData.product_details = dataArray;
        createData = formData;
        $.proxy(f.submitHandler(createData))

    });

    // When data enter after that button would be enable

    f.productPop.on('change keyup paste', function(e) {
        if($('#select_popup_product').find('option:selected').length > 0 && ($('#qty').val() > 0)){
            f.productPopupSaveButton.prop( 'disabled', false );
        }else{
            f.productPopupSaveButton.prop( 'disabled', true );
        }
    });

    $('.cancel-btn').on('click', function(){
        f.productPop.modal('hide');
    });

    // Po print pdf click
    $('.po_print_btn').on('click', function(e){
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

    $('#qty').on('change keyup paste', function(e) {
        if($('#qty').val() <= 0){
            $('#quantity_name_error').text('Quantity should be greater than 0');
        }else{
            $('#quantity_name_error').text('');
        }
    });

    $('.product-popup-cancel-btn').on('click', function(e){
        $('#select_popup_product').val('').trigger('change');
    }); 
}
