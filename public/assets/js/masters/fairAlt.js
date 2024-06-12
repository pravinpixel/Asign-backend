var Fair = function (config) {
	var $html = $("html");
	var f = this;
	f.config = config;
	f.page = $("#main_content");
    f.create_btn = f.page.find(".open_create");
	f.popup = f.page.find("#dynamic_popup_modal");
    f.title = f.popup.find('.modal-title');
    f.form = f.popup.find("#dynamic_popup_modal_form");
    f.confirm = f.popup.find("#dynamic_confirm");
	f.table = f.page.find("#table_body");
    f.toasterConfig = {
        timeOut: 5000
        //types warning/success/error
    }

    f.formValidation = false;
    f.httpCall = true;
    f.httpPostPath = "";

	// get all lists
    $.get(f.config.links.list, function (data) {
        f.table.html(data);
    });

    // Functions
    f.toasterHandler = function (type, msg){
        toastr[type](msg, f.toasterConfig);
    }
    f.popupHandler = function (el) {
        let id = $(el).attr("data-id");
        if(!id){
            f.title.text(f.config.content.addTitle);
        }
        else{
            f.title.text(f.config.content.editTitle);
        }

        var http = $.ajax({
            url: f.config.links.model + '/' + id,
            type: "GET",
            processData: false,
            contentType: false,
        });
        http.done(function (data) {
            f.popup.find("#dynamic_popup_modal_content").html(data);
            f.popup.find("#dynamic_confirm").hide();
            f.popup.modal("show");
        });
        http.fail(function () {
            
        });
        http.always(function () {
           
        });
    }
    f.submitHandler = function(formData){
        var http = $.ajax({
            url: f.config.links.save,
            type: "POST",
            processData: false,
            contentType: false,
            data: formData
        });
        http.done(function (data) {   
            if(typeof data =='object'){
                f.popup.modal("hide");
                f.toasterHandler("success", "example message");    
            }
            else{
                f.popup.modal("hide");
                f.toasterHandler("error", "Something Wrong ...!");   
            }
        });
        http.fail(function () {
            f.toasterHandler("error", "Something Wrong ...!");  
        });
        http.always(function () {
           
        });
    }
    f.validateHandler = function (formData) {
        var http = $.ajax({
            url: f.config.links.validate,
            type: "POST",
            processData: false,
            contentType: false,
            data: formData
        });
        http.done(function (data) {
            f.popup.find("#dynamic_popup_modal_content").html(data);     
            f.popup.find("#dynamic_confirm").hide();   
            let status = f.popup.find("#status").val();
            if(status=="complete"){
                f.popup.fadeOut(300, function() {
                    f.popup.find("#dynamic_popup_modal_form").hide();
                    f.popup.find("#dynamic_confirm").show();
                });
                f.popup.fadeIn(300); 
            }
            else{
                f.popup.find("#dynamic_popup_modal_form").show();
                f.popup.find("#dynamic_confirm").hide();
            }

            // if(typeof data =='object'){
            //     f.formValidation = true;
            //     f.popup.fadeOut(300, function() {
            //         f.popup.find("#dynamic_popup_modal_form").hide();
            //         f.popup.find("#dynamic_confirm").show();
            //         f.toasterHandler("success", "example message");
            //     });
            //     f.popup.fadeIn(300);     
            // }
            // else{
            //     f.popup.find("#dynamic_popup_modal_content").html(data);
            //     f.popup.find("#dynamic_confirm").hide();
            // }
        });
        http.fail(function () {
            
        });
        http.always(function () {
           
        });
    }
    f.cancelHandler = function (){
        f.popup.fadeOut(300, function() {
            f.popup.find("#dynamic_popup_modal_form").show();
            f.popup.find("#dynamic_confirm").hide();
        });
        f.popup.fadeIn(300); 
    }
    f.confirmHandler = function(){
        f.form.trigger('submit');
    }
    // Events
    f.page.on('click', '.open_create', function(e){
        e.preventDefault();
        $.proxy(f.popupHandler(e.currentTarget));       
    });
    f.page.on('click', '#cancel_confirm', function(e){
        e.preventDefault();
        $.proxy(f.cancelHandler());       
    });
    f.page.on('click', '#submit_confirm', function(e){
        e.preventDefault();
        var fData = $("#dynamic_popup_modal_form").serializeArray();
        var frmData = new FormData;
        $.each(fData, function(ind, field) {
            frmData.append(field.name, field.value);
        });
        $.proxy(f.submitHandler(frmData));      
    });
	f.table.on('click', '.open_edit', function(e){
		e.preventDefault();
		$.proxy(f.popupHandler(e.currentTarget));       
	});
    $html.on("submit", "#dynamic_popup_modal_form", function(e) {
        e.preventDefault();
        var fData = $(this).serializeArray();
        var frmData = new FormData;
        $.each(fData, function(ind, field) {
            frmData.append(field.name, field.value);
        });
        $.proxy(f.validateHandler(frmData));
    });     
}