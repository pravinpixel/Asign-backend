var CreateDamaged = function (config) {
    var $html = $("html");
    var cd = this;

    cd.config = config;
    cd.page = $("#main_content");
    cd.popupModal = $("#popup_form_modal");
    cd.popupModalContent = cd.popupModal.find("#popup_form_content");
    cd.createForm = cd.page.find("#create_form");
    cd.createBtn = cd.page.find("#create_btn");
    cd.productTable = cd.page.find("#product_table");
    cd.existLocation = cd.page.find("#exist_location");
    cd.productType = cd.createForm.find(".product_type");
    cd.productTypeHidden = cd.createForm.find("#product_type");
    cd.popupOpenBtn = cd.createForm.find("#trigger_popup_form");

    // Functions
    cd.enablePopup = function(){
        let hasLocation = cd.createForm.find("#location_id").val();
        if(hasLocation){
            cd.popupOpenBtn.attr("disabled", false);
        }
        else{
            cd.popupOpenBtn.attr("disabled", true);
        }
    };
    cd.enbaleButton = function(){
        let tds = cd.productTable.find("td.qty");
        let total = 0;
        $(tds).each(function(fine, el){
            let td = Number($(el).html());
            total += td;
        });

        if(total > 0){
            cd.createBtn.attr("disabled", false);
        }
        else{
            cd.createBtn.attr("disabled", true);
        }
    };
    cd.isEmpty = function (value) {
        return value === null || value === undefined || value === '';
    };
    cd.isFilled = function (formID, btn){
        let allValues = $(formID).serializeArray();
        var isAnyValueEmpty = allValues.some(function(obj) {
            for (var key in obj) {
                if (obj.hasOwnProperty(key) && cd.isEmpty(obj[key])) {
                    return true;
                }
            }

            return false;
        });

        $(btn).prop('disabled', isAnyValueEmpty);
    };
    cd.initiatePlugins = function () {
        $(".select2Box").each(function(){
            var placeholder = $(this).attr('data-placeholder');
            $(this).select2({
                placeholder: placeholder,
                minimumResultsForSearch: Infinity,
            });
        });
        
        $.proxy(cd.enbaleButton());
        $.proxy(cd.enablePopup());
    };
    cd.fetchProducts = function(){
        var http = $.ajax({
            url: cd.config.links.productList +'/'+ cd.config.data.damageID,
            type: "GET",
            processData: false,
            contentType: false
        });
        http.done(function (data) {
            cd.productTable.html(data);
            $.proxy(cd.initiatePlugins());
        });
        http.fail(function () {

        });
    };
    cd.submitHandler = function(formData){
        var http = $.ajax({
            url: cd.config.links.save,
            type: "POST",
            processData: false,
            contentType: false,
            data: formData
        });
        http.done(function (data) {
            cd.popupModal.modal("hide");
            $.proxy(cd.fetchProducts());
        });
        http.fail(function () {

        });
    };
    cd.damageHandler = function(){
        var http = $.ajax({
            url: cd.config.links.updateDamagedLabels,
            type: "POST",
            processData: false,
            contentType: false,
            data: {
                id: "DIL000001"
            }
        });
        http.done(function (data) {
            if(data?.status=="success"){
                window.location.replace(cd.config.links.list);
            }
        });
        http.fail(function () {

        });
    };
    cd.clearProducts = function(location_id){
        var http = $.ajax({
            url: cd.config.links.clear + '/' + location_id,
            type: "GET",
        });
        http.done(function (data) {
            if(data?.status=="deleted"){
                $.proxy(cd.fetchProducts());
            }
        });
        http.fail(function () {

        });
    };

    // Events
    cd.page.on("click", "#trigger_popup_form", function(e) {
        e.preventDefault();
        var http = $.ajax({
            url: cd.config.links.labels,
            type: "GET",
            processData: false,
            contentType: false,
        });
        http.done(function (data) {
            cd.popupModalContent.html(data);
            $.proxy(cd.initiatePlugins());
            cd.popupModal.modal("show");
        });
        http.fail(function () {

        });
    });
    cd.page.on("change", "#create_form", function(e) {
        e.preventDefault();
        $.proxy(cd.isFilled("#create_form", "#product_submit_btn"));
        $.proxy(cd.enablePopup());
    });
    cd.page.on("select2:select", ".locationbox", function(e) {
        e.preventDefault();
        var data = e.params.data;
        // let existVal = $("#exist_location").val();
        // let currentVal = data?.id;
        console.log(data?.id, "daat");
        $.proxy(cd.clearProducts(data?.id));
    });
    cd.page.on("select2:select", cd.productType, function(e) {
        e.preventDefault();
        var data = e.params.data;
        cd.productTypeHidden.val(data?.id);
        $.proxy(cd.isFilled("#create_form", "#product_submit_btn"));
        $.proxy(cd.enablePopup());
    });
    cd.page.on("submit", "#create_form", function(e) {
        e.preventDefault();
        var fData = $(this).serializeArray();
        var frmData = new FormData;
        $.each(fData, function(ind, field) {
            frmData.append(field.name, field.value);
        });

        $.proxy(cd.submitHandler(frmData));
    });
    cd.page.on("click", "#product_submit_btn", function(e) {
        e.preventDefault();
        cd.createForm.trigger("submit");
    });
    cd.page.on("click", "#create_btn", function(e) {
        e.preventDefault();
        $.proxy(cd.damageHandler());
    });

    // Init page is ready!
    $.proxy(cd.initiatePlugins());
}
