var ScannerApp = function (config) {
    var $html = $("html");
    var app = this;

    let upload_images = [];
    app.html = $("html");
    app.config = config;
    app.remove_image_modal = $("#remove_image_modal_confirm");
    app.remove_image_modal_alt = $("#remove_image_modal_confirm_alt");
    app.discard_changes_modal = $("#discard_modal_confirm");
    app.image_match_modal = $("#image_match_modal_confirm");
    app.void_modal = $("#void_modal_confirm");
    app.label_prcessing = $("#label_processing_modal_confirm");
    app.exit_labelling = $("#edit_modal_confirm");
    app.delete_label_confirm = $("#delete_modal_confirm");
    app.delete_label_confirm_alt = $("#delete_modal_confirm_alt");
    app.scanner_modal = $("#scanner_app_modal");
    app.scanner_content = app.scanner_modal.find("#scanner_app_content");
    app.scanner_form = app.scanner_modal.find("#scanner_form");
    app.scanner_form_body = app.scanner_modal.find("#scanner_form_body");
    app.approve_submit = app.scanner_modal.find("#approve_submit");
    app.close_modal_btn = app.scanner_modal.find("#close_scanner_modal");
    app.void_form = app.void_modal.find("#void_form");

    app.upload_view = "#upload_view";
    app.matching_p = "matching_p";
    app.envelope_helper = "#envelope_helper";
    app.envelope_clear = "#clear_envelope";
    app.label_helper = "#label_helper";
    app.label_clear = "#clear_label";
    app.loader = '<div class="spinner-border spinner-border-sm" style="color: #cbcbcb" role="status"><span class="visually-hidden">Loading...</span></div>';
    app.cropper = null;
    app.croppop = null;
    app.editedBlob = null;
    app.default_type = "crop";
    app.submit_btn = "#submit_btn";
    app.submit_btn_alt = "#submit_btn_alt";


    var $image = app.scanner_modal.find('#upload_image');

    var options = {
        viewMode: 1,
        background: false,
        zoomable: false,
        autoCrop: true,
        guides: true,
        autoCropArea: 0.8,
        crop: async event => {
            console.log("from options");
            let imgUrl = await new Promise(resolve => {
                app.cropper.getCroppedCanvas().toBlob((blob) => {
                    app.editedBlob = blob;
                }, 'image/png', 1);

                $("#is_form_changed").val("croped");
                $.proxy(app.validateForm("#scanner_form"));
            });
        }
    };

    // Functions
    app.isEmpty = function (value) {
        return value === null || value === undefined || value === '';
    };
    app.scriptInit = function () {
        $(app.submit_btn).prop('disabled', true);
        $(app.submit_btn_alt).prop('disabled', true);
    };
    app.validateForm = function (formID) {
        let allValues = $(formID).serializeArray();
        allValues = allValues.filter(function (obj) {
            return obj.name !== 'label_hidden';
        });
        var isAnyValueEmpty = allValues.some(function (obj) {
            for (var key in obj) {
                if (obj.hasOwnProperty(key) && app.isEmpty(obj[key])) {
                    return true;
                }
            }
            return false;
        });

        if ($('#envelope').length) {
            if (!upload_images.length) {
                isAnyValueEmpty = true;
            }
        }
        $(app.submit_btn).prop('disabled', isAnyValueEmpty);
        $(app.submit_btn_alt).prop('disabled', isAnyValueEmpty);
    };
    app.cropInit = function () {
        $image = app.scanner_modal.find('#upload_image');
        $image.on({
            ready: function (e) {
                app.cropper = $image.data('cropper');

                if (app.default_type == "crop") {
                    $("#object_crop").addClass("active-btn");
                }
                else {
                    $("#object_rotate").addClass("active-btn");
                }
            },
            crop: async event => {
                // First time init
                if (!app.cropper) {
                    app.cropper = $image.data('cropper');
                }
            }
        }).cropper(options);
    };
    app.showVoidButton = function () {
        let envelope = $('#envelope').val();
        let label = $('#label').val();
        let form_valid = $('#is_form_valid').val();

        if (envelope && label && form_valid === "valid") {
            $('#void_btn').show();
        } else {
            $('#void_btn').hide();
        }
    };
    app.getMatchingValue = function () {
        var frmData = new FormData();
        frmData.append("request_id", app.config.request_id);
        var http = $.ajax({
            url: app.config.links.matching,
            type: "POST",
            data: frmData,
            processData: false,
            contentType: false,
        });
        http.done(function (response) {
            // console.log("response", response);
        });
    };
    app.uploadHandler = function (form_for, formData) {

        $(app.submit_btn).prop('disabled', true).html('Loading...');
        formData.append("request_id", app.config.request_id);
        formData.append("image_edit_type", app.default_type);
        var http = $.ajax({
            url: app.config.links.upload,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
        });
        http.done(function (response) {

            if (response['return_step'] == 'rotate') {
                app.scanner_content.html(response['scanner']);
            } else if (response['return_step'] === 'only_crop_image') {

                var old_image = response['old_image'];
                var new_image = response['name'];
                var index = upload_images.indexOf(old_image);
                if (index !== -1) {
                    upload_images[index] = new_image;
                }
                var src = $('#label-image-object').find('img[src="' + old_image + '"]');
                src.attr('src', new_image);
                console.log($('.label-image-list').find('img[src="' + old_image + '"]'))
                app.scanner_content.show();
                $('#child-image-cropper').removeClass('modal-dialog-centered').hide().html('');
                $(app.submit_btn).prop('disabled', false).html('Next');
                return false;
            }
            if (response['return_step'] === "inventory_label" || response['return_step'] === "inventory_label_child" || response['return_step'] === "auth_label" || response['return_step'] === "auth_label_child") {
                $.proxy(app.getScannerAppStep(response['return_step'], "", ""));
            }
            else {
                app.scanner_content.fadeOut(200, function () {
                    if (response['header']) {
                        $('#show-continue-notify').val(1);

                        //  $('.label_step_notify').show();
                        $('#view-header').html(response['header']);
                    }
                    app.scanner_content.html(response['scanner']);
                    app.config.current_step = form_for;

                    if (form_for == "object_match") {
                        $.proxy(app.cropInit());
                    }
                    else if (form_for == "edit_uploaded_image") {
                        if (app.cropper) {
                            app.cropper.destroy();
                            app.cropper = null;
                        }
                    }

                    $(app.submit_btn).prop('disabled', false).html('Next');

                }).fadeIn(200);
            }
        });
        http.fail(function (jqXHR, textStatus, errorThrown) {
            var errors = jqXHR.responseJSON.error;
            var errorHtml = '';
            $.each(errors, function (key, value) {
                errorHtml += value[0] + '<br>';
            });
            toastr.error(errorHtml);
            $(app.submit_btn).prop('disabled', false).html('Next');
        });
    };
    app.deleteHandler = function () {
        var frmData = new FormData();
        frmData.append("request_id", app.config.request_id);
        var http = $.ajax({
            url: app.config.links.delete,
            type: "POST",
            data: frmData,
            processData: false,
            contentType: false,
        });
        http.done(function (response) {
            $("#scanner_form_body").fadeOut(200, function () {
                $("#scanner_form_body").html(response);
                $('#trigger_image_match').prop('disabled', true);
                $('.disabled-next').prop('disabled', true);
                app.editedBlob = null;
            }).fadeIn(200);
        });
    };
    app.submitHandler = function (formData) {

        $(app.submit_btn).prop('disabled', true).html('Loading...');
        formData.append("request_id", app.config.request_id);
        formData.delete('image');
        if (upload_images.length) {
            for (let i = 0; i < upload_images.length; i++) {
                formData.append('images[]', upload_images[i]);
            }
        }

        var http = $.ajax({
            url: app.config.links.save,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
        });
        http.done(function (response) {

            app.config.current_step = response.form_for;

            if (response['header']) {
                $('#view-header').html(response['header']);
            }
            if (response['activity']) {
                $('#activity-log').prepend(response['activity']);
            }

            $('#objectLabels').html(response['object-label']);
            app.scanner_content.html(response['scanner']);
            if (response.status == "approved") {
                $('#show-continue-notify').val(0);
                app.scanner_modal.modal("hide");
                //  $('.label_step_notify').hide();
            }
            if (response.formtype == "standalone") {
                app.scanner_modal.modal("hide");
            }

            loadNewImage();
            $('#envelope_helper').html('');
            $('#label_helper').html('');
            $(app.submit_btn).prop('disabled', false).html('Next');
            $('#approve_submit').prop('disabled', false).html('Approve');
            $('#submit_and_add_child_label').prop('disabled', false).html('Add Child Labels');
            $.proxy(app.validateForm("#scanner_form"));
            loadViewBoxLabel();
        });
        http.fail(function (jqXHR, textStatus, errorThrown) {
            $(app.submit_btn).prop('disabled', false).html('Next');
            $('#approve_submit').prop('disabled', false).html('Approve');
            $('#submit_and_add_child_label').prop('disabled', false).html('Add Child Labels');
            $.proxy(app.validateForm("#scanner_form"));
            toastr.error(jqXHR.responseJSON.error);
        });
    };
    app.envelopeHandler = function (envelope_id) {
        $(app.envelope_helper).html(app.loader);
        var http = $.ajax({
            url: app.config.links.envelopeValidator + '?envelope_id=' + envelope_id,
            type: "GET",
            processData: false,
            contentType: false,
        });
        http.done(function (response) {
            setTimeout(() => {
                $(app.envelope_helper).html(response?.data);
            }, 400);
        });
    };
    app.labelHandler = function (label_id, current_step) {
        $(app.label_helper).html(app.loader);

        var labelHidden = $('#label_hidden').val();
        var label = $('#label').val();

        if (labelHidden !== label) {
            var http = $.ajax({
                url: app.config.links.labelValidator + '?label_id=' + label_id + '&current_step=' + current_step,
                type: "GET",
                processData: false,
                contentType: false,
            });
            http.done(function (response) {
                $("#is_form_valid").val(response?.status);
                $.proxy(app.validateForm("#scanner_form"));
                $(app.label_helper).html(response?.data);
                $.proxy(app.showVoidButton());
            });
        } else {
            $("#is_form_valid").val('valid');
            $(app.label_helper).html('');
        }
    };
    app.getScannerAppStep = function (stepKey, direction = "", formtype = "") {
        var http = $.ajax({
            url: app.config.links.step,
            type: "POST",
            data: {
                request_id: app.config.request_id,
                step: stepKey,
                direction: direction,
                formtype: formtype
            }
        });
        http.done(function (response) {
            app.scanner_modal.modal("show");
            app.scanner_content.fadeOut(200, function () {
                app.config.current_step = stepKey;
                app.scanner_content.html(response);
                // Setup re initialize after api success
                $.proxy(app.scriptInit());
                if (stepKey == "object_match") {
                    app.discard_changes_modal.modal("hide");
                    if (app.cropper) {
                        app.cropper.destroy();
                        app.cropper = null;
                    }
                } else if (stepKey == "edit_uploaded_image" || stepKey == "edit_uploaded_image_alt") {
                    $.proxy(app.cropInit());
                } else if (stepKey == "inventory_label" || stepKey == "auth_label" || stepKey == "inventory_label_child" || stepKey == "auth_label_child") {
                    loadNewImage();
                    app.label_prcessing.modal("hide");
                    app.image_match_modal.modal("hide");
                    $.proxy(app.validateForm("#scanner_form"));
                }
            }).fadeIn(200);
        });
    };
    app.labelImageDeleteHandler = function (type, img, _this) {

        $('#removeLabelImage').prop('disabled', true).html('Loading...');

        var http = $.ajax({
            url: app.config.links.removeImage,
            type: "DELETE",
            data: {
                id: app.config.request_id,
                type: type,
                image: img
            }
        });
        http.done(function (response) {
            _this.closest("a").remove();
            $('#objectLabels').html(response['object-label']);
            removeImageDiv = '';
            upload_images = upload_images.filter(function (item) {
                return item !== img;
            });
            uploadImageDivStyle();
            $.proxy(app.validateForm("#scanner_form"));
            app.remove_image_modal.modal("hide");
            $('#removeLabelImage').prop('disabled', false).html('Remove');
            loadViewBoxLabel();
        });
    };
    app.fetchImageToEdit = function (data) {
        console.log("data", data);
        var http = $.ajax({
            url: app.config.links.extendedEdit,
            type: "POST",
            data: data
        });
        http.done(function (response) {
            app.scanner_modal.modal("show");

            setTimeout(() => {
                app.scanner_content.hide();
                $('#child-image-cropper').addClass('modal-dialog-centered').show().html(response);
                $.proxy(app.cropInit());
            }, 200)

            // app.scanner_content.fadeOut(200, function () {
            //     app.scanner_content.html(response);
            //
            //     $.proxy(app.cropInit());
            // }).fadeIn(200);
        });
    }

    // Events
    $html.on("click", "#start_labelling", function (e) {
        e.preventDefault();
        $.proxy(app.getScannerAppStep(app.config.current_step, "", ""));
    });
    $html.on("click", "#next_step", function (e) {
        e.preventDefault();
        let next = e.target.getAttribute("data-next");
        let direction = e.target.getAttribute("data-direction");
        $.proxy(app.getScannerAppStep(next, direction, ""));
    });
    $html.on("click", "#next_span", function (e) {
        e.preventDefault();
        let next = $(this).attr("data-span");
        let formtype = $(this).attr("data-formtype");
        $.proxy(app.getScannerAppStep(next, "", formtype));
    });
    $html.on("click", ".fetch_image_to_edit", function (e) {
        e.preventDefault();
        let from_step = $(this).attr("data-from");
        let image = $(this).attr("data-img");
        let type = $(this).attr("data-imgtype");
        let old_url = $(this).closest('a').find('.label-image-list').attr('src');
        let data = {
            request_id: app.config.request_id,
            from_step: from_step,
            extended_img: image,
            type: type,
            old_url: old_url
        }

        $.proxy(app.fetchImageToEdit(data));
    });
    $html.on("click", ".craft_btn", function (e) {
        var $this = $(this);
        var data = $this.data();
        var cropper = $image.data('cropper');

        if ($this.prop('disabled') || $this.hasClass('disabled')) {
            return;
        }

        if (cropper && data.method) {
            app.default_type = data.method;
            data = $.extend({}, data);
            // Clear cropper options
            $image.cropper('clear');

            // Enable cropper options
            switch (data.method) {
                case 'rotate':
                    $image.cropper('enable');
                    $image.cropper('rotate');
                    $("#object_rotate").addClass("active-btn");
                    break;

                case 'crop':
                    $image.cropper('enable');
                    $image.cropper('crop');
                    $("#object_crop").addClass("active-btn");
                    break;
            }

            // Apply task rotate/crop
            $image.cropper(data.method, data.option);

            // Re-assign cropper options
            switch (data.method) {
                case 'rotate':
                    $image.cropper('disable');
                    $("#object_crop").removeClass("active-btn");
                    break;

                case 'crop':

                    $("#object_rotate").removeClass("active-btn");
                    break;
            }
        }
    });
    $html.on("change", "#scanner_form", function (e) {
        $.proxy(app.validateForm("#scanner_form"));
    });
    $html.on("dragenter, focus, click", "#object", function (e) {
        $(".upload_file").addClass("focus_outline");
    });
    $html.on("dragleave blur drop", "#object", function (e) {
        $(".upload_file").removeClass("focus_outline");
    });
    $html.on("change", "#object", function (e) {
        $('#scanner_form').submit();
    });
    $html.on("change paste focus blur", "#envelope", function (e) {
        let envelope_id = $(this).val();
        if (envelope_id !== "") {
            $(app.envelope_helper).show();
            $.proxy(app.envelopeHandler(envelope_id));
            $(app.envelope_clear).addClass("show");
        } else {
            $(app.envelope_helper).hide();
            $(app.envelope_clear).removeClass("show");
            $(app.submit_btn).prop('disabled', true);
            $(app.submit_btn_alt).prop('disabled', true);
        }

        $.proxy(app.showVoidButton());
    });
    $html.on("change paste focus blur", "#label", function (e) {
        let label_id = $(this).val();
        let current_step = $("#type").val();
        if (label_id != "") {
            $(app.label_helper).show();
            $.proxy(app.labelHandler(label_id, current_step));
            $(app.label_clear).addClass("show");
        } else {
            $(app.label_helper).hide();
            $(app.label_clear).removeClass("show");
            $(app.submit_btn).prop('disabled', true);
            $(app.submit_btn_alt).prop('disabled', true);
        }

        $.proxy(app.showVoidButton());
    });
    $html.on("click", "#delete_img", function (e) {
        $.proxy(app.deleteHandler());
    });
    $html.on("click", "#clear_envelope", function (e) {
        let envelope = $("#envelope").val();
        if (envelope) {
            app.delete_label_confirm_alt.modal("show");
        }
    });
    $html.on("click", "#clear_label", function (e) {
        let label = $("#label").val();
        if (label) {
            app.delete_label_confirm.modal("show");
        }
    });
    $html.on("change", "#has_removed", function (e) {
        if (this.checked) {
            $("#remove_btn").attr("disabled", false);
        }
        else {
            $("#remove_btn").attr("disabled", true);
        }
    });
    $html.on("click", "#remove_btn", function (e) {
        $("#label").val('');
        $("#label").trigger("focusout");
        app.delete_label_confirm.modal("hide");
    });
    $html.on("click", "#remove_btn_alt", function (e) {
        $("#envelope").val('');
        $("#label").val('');
        $("#envelope").trigger("focusout");
        $("#label").trigger("focusout");
        app.delete_label_confirm_alt.modal("hide");
    });
    $html.on("submit", "#scanner_form", function (e) {
        e.preventDefault();
        var form_for = $(this).find("#form_for").val();
        var frmData = new FormData(this);
        if (form_for == "object_match" || form_for == "edit_uploaded_image" || form_for == "edit_uploaded_image_alt") {
            if (app.editedBlob) {
                frmData.append('object', app.editedBlob, 'updated_image.jpg');
            }
            console.log("upload", frmData);
            $.proxy(app.uploadHandler(form_for, frmData));
        } else {
            console.log("submit", frmData);
            $.proxy(app.submitHandler(frmData));
        }
    });
    $html.on("click", "#trigger_discard", function (e) {
        e.preventDefault();
        app.discard_changes_modal.modal("show");
    });
    $html.on("click", "#trigger_image_match", function (e) {
        e.preventDefault();
        app.image_match_modal.modal("show");
    });
    $html.on("click", "#void_btn", function (e) {
        e.preventDefault();
        app.void_modal.modal("show");

        // Value tranfer #scanner_form form to #void_form
        var form = $("#scanner_form");
        var envelope_code = form.find("#envelope").val();
        var label_code = form.find("#label").val();
        var void_form = app.void_modal.find("#void_form");
        void_form.find("#envelope_code").val(envelope_code);
        void_form.find("#label_code").val(label_code);
    });
    $html.on("submit", "#void_form", function (e) {
        e.preventDefault();
        var frmData = new FormData(this);
        frmData.append("request_id", app.config.request_id);
        let type = $('input[name="type"]').val();
        frmData.append("current_step", type);
        var http = $.ajax({
            url: app.config.links.voidSave,
            type: "POST",
            data: frmData,
            processData: false,
            contentType: false,
        });
        http.done(function (response) {
            if (!response.data.message) {
                app.void_modal.modal("hide");
                $('#void_form')[0].reset();
                $('#scanner_form')[0].reset();
                $(app.label_helper).html('');
                $(app.envelope_helper).html('');
                $('#void_btn').hide();
                upload_images = [];
                $('#label-image-object').find('a').remove();
                uploadImageDivStyle();
                $.proxy(app.validateForm("#scanner_form"));
                $(app.envelope_clear).removeClass("show");
                $(app.label_clear).removeClass("show");

            } else {
                $('#error_message').text(response.data.message);
            }
        });
    });
    $html.on("click", "#submit_btn_alt", function (e) {
        e.preventDefault();
        let type = $("#type").val();

        if (type == "auth_label" || type == "inventory_label") {
            $("#auth_inventory_content").show();
            $("#auth_inventory_child_content").hide();
        }
        else {
            $("#auth_inventory_child_content").show();
            $("#auth_inventory_content").hide();
        }
        app.label_prcessing.modal("show");
    });
    $html.on("click", "#approve_submit, #submit_and_add_child_label", function (e) {
        e.preventDefault();
        let status = $(this).attr('data-status');
        $('#is_approved').val(status);
        $('#scanner_form').trigger('submit');
        app.label_prcessing.modal("hide");
    });
    var removeImageDiv = '';
    $html.on("click", ".ext_img_remove", function (e) {
        e.preventDefault();
        let type = $(this).attr("data-type");
        let img = $(this).attr("data-img");

        $('#removeLabelImage').attr('data-type', type).attr('data-img', img);
        removeImageDiv = $(this);
        app.remove_image_modal.modal("show");
    });
    $html.on("click", ".deleteLabelImage", function (e) {
        e.preventDefault();
        let img = $(this).closest('a').find('.label-image-list').attr('src');
        $('#removeLabelImage').attr('data-img', img);
        removeImageDiv = $(this);
        app.remove_image_modal.modal("show");
    });
    $html.on("click", "#removeLabelImage", function (e) {
        e.preventDefault();
        let type = $('input[name="type"]').val();
        let img = $(this).attr("data-img");
        $.proxy(app.labelImageDeleteHandler(type, img, removeImageDiv));
    });
    $html.on("click", "#close_notify", function (e) {
        e.preventDefault();
        $(this).closest('.label_step_notify').fadeOut();
    });
    $html.on("click", "#close_scanner_modal", function (e) {
        app.exit_labelling.modal("show");
    });
    $html.on("click", "#exit_scanner_modal", function (e) {
        app.exit_labelling.modal("hide");
        app.scanner_modal.modal("hide");
    });

    // Page Ready
    $.proxy(app.scriptInit());

    //    label image upload
    $(document).on('change', "#upload-image-label-object", function () {
        const files = this.files;
        const formData = new FormData()
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i])
        }
        let type = $('input[name="type"]').val();

        var _this = $(this);

        formData.append("request_id", app.config.request_id);
        formData.append("current_step", type);

        $.ajax({
            url: baseUrl + '/protect-request/multi-file-upload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                let imagesDiv = "";
                if (response.success) {
                    var dataArr = response.data;
                    $.each(dataArr, function (i, e) {
                        upload_images.push(e.image);
                        imagesDiv += `<a  class="image position-relative">
                                <img src="${e.image}" alt="img" class="img-fluid label-image-list">
                                <div class="delete-inner cP">
                                    <img class="deleteLabelImage" src="${app.config.deleteIcon}" alt="img-del" width="40">
                                    <img class="fetch_image_to_edit" data-from="`+ e.step + `" data-img="${e.id}" data-imgtype="temp" src="${app.config.editIcon}" alt="img-del" width="40">
                                </div>
                            </a>`;
                    });
                    $('#label-image-object').find('#width-100').before(imagesDiv);
                }
                uploadImageDivStyle();
                $.proxy(app.validateForm("#scanner_form"));
                _this.val('');
            }
        })
    });

    function uploadImageDivStyle() {
        if (upload_images.length) {
            $("#width-100").removeClass("w-100");
            $("#imageLabel1").addClass("d-none");
            $("#imageLabel2").removeClass("d-none");
        } else {
            $("#width-100").addClass("w-100");
            $("#imageLabel1").removeClass("d-none");
            $("#imageLabel2").addClass("d-none");
        }
    }

    function loadNewImage() {
        upload_images = [];
        $('#label-image-object .label-image-list').each(function () {
            var src = $(this).attr('src');
            if (src) {
                upload_images.push(src);
            }
        });
    }

    function loadViewBoxLabel() {
        $(".view_box_labelI").unbind().removeData();
        $('.view_box_labelI').viewbox({
            setTitle: false,
            margin: 40,
        });
        $(".view_box_labelA").unbind().removeData();
        $('.view_box_labelA').viewbox({
            setTitle: false,
            margin: 40,
        });
    }

    loadViewBoxLabel();

    $(document).on('click', '#close-image-cropper', function () {
        app.scanner_content.show();
        $('#child-image-cropper').hide().html('');
    });

    $(document).on('show.bs.modal', '#scanner_app_modal', function () {
        $('.label_step_notify').hide();
    });
    $(document).on('hide.bs.modal', '#scanner_app_modal', function () {
        if ($('#show-continue-notify').val() == '1') {
            $('.label_step_notify').show();
        }
    });

}
