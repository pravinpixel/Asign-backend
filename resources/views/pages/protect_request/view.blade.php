@extends('layouts.index')
@section('title', 'Asign Request')
@section('style')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ asset('css/demo/asign_protect_request.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.viewbox.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.uploader.css') }}"/>
    <link rel="stylesheet" type="text/css" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/rcrop.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/cropper.css') }}"/>
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.filer.css') }}"/> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/themes/jquery.filer-dragdropbox-theme.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.toast.min.css') }}"/>
    <style>
        .removeSelect .select2-results__option.select2-results__option--selected {
            display: none !important;
        }

        .edit-wrapper .image-preview {
            height: auto;
        }

        @media (min-width: 576px) {
            .scanner-app-modal .modal-dialog {
                max-width: 720px !important;
            }

            .modal-dialog.confirm-modal {
                max-width: 381px !important;
            }

            .modal-dialog.confirm-modal.confirm-modal-void {
                max-width: 475px !important;
            }

/*            #discard_modal_confirm{
                max-width: 361px !important;
            }*/
        }

        .scanner-app-modal .modal-footer {
            justify-content: space-between
        }


        .custom_filer {
            display: flex;
            flex-direction: row;
            padding-top: 16px;
        }

        .jFiler-theme-dragdropbox {
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .jFiler-item-container {
            margin: 0px !important;
            border: 0px !important;
            padding: 0px !important;
        }

        .jFiler-items-grid .jFiler-item .jFiler-item-container .jFiler-item-thumb {
            width: 173px !important;
            height: 173px !important;
            position: relative;
            margin-right: 0px !important;
            border-radius: 4px !important;
        }

        .jFiler-items-grid .jFiler-item .jFiler-item-container .jFiler-item-assets {
            margin-top: 0px !important;
        }

        .jFiler-items-grid .jFiler-item .jFiler-item-container .jFiler-item-info {
            display: table;
            padding: 16px 16px 0px 0px;
            text-align: right;
        }

        .jFiler-items-grid .jFiler-item .jFiler-item-container .jFiler-item-info .jFiler-item-trash-action {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: #1D1D1D;
            border-radius: 40px;
        }

        #multi_preview {
            display: inline-block;
        }

        .jFiler-input-dragDrop-custom {
            display: flex;
            width: 173px;
            height: 173px;
            padding: 16px;
            color: #1D1D1D;
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' stroke='%23B5B5B5FF' stroke-width='3' stroke-dasharray='6%2c 10' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            text-align: center;
            -webkit-transition: box-shadow 0.3s,
            border-color 0.3s;
            -moz-transition: box-shadow 0.3s,
            border-color 0.3s;
            transition: box-shadow 0.3s,
            border-color 0.3s;

            justify-content: center;
            align-items: center;
            border-radius: 4px !important;
        }

        .scanner-app-modal .modal-footer {
            padding: 24px 32px;
        }

        .scanner-app-modal #scanner_form_body > .container-fluid {
            padding-left: 0px;
            padding-right: 0px;
        }

        /* Preview*/
        .scanner-app-modal .modal-header h1.modal-title,
        .scanner-app-modal .scanning-steps h4 {
            font-size: 24px;
            font-family: 'neue-montreal-medium' !important;
            color: #000000;
        }

        .scanner-app-modal .scanning-steps h4 {
            margin-bottom: 10px;
            color: #1d1d1d !important;
        }

        .scanner-app-modal .scanning-steps .step-card {
            margin-top: 24px;
        }

        .scanner-app-modal .scanning-steps .step-card .step-card-title {
            font-size: 20px;
            font-family: 'neue-montreal-medium' !important;
            color: #696969;
            margin-top: 16px;
            margin-bottom: 0px;
            font-weight: 500;
        }

        .labelling-process-modal-body {
            padding: 24px 32px 120px 32px;
        }

        .scanner-app-modal .scanning-steps .step-card .step-card-content {
            font-size: 18px;
            font-family: 'neue-montreal-regular' !important;
            color: #696969;
            margin-bottom: 16px;
            line-height: 30px;
        }

        .scanner-app-modal .scanning-steps .step-card .step-card-content > span:last-child,
        .scanner-app-modal .scanning-steps .icon-para > span:last-child {
            padding-left: 8px;
        }

        .scanner-app-modal .scanning-steps h4.click-next {
            margin-top: 40px;
            margin-bottom: 10px;
            color: #1D1D1D !important;
        }

        .scanner-app-modal .scanning-steps .icon-para {
            font-size: 18px;
            font-family: 'neue-montreal-medium' !important;
            color: #1D1D1D;
            margin-top: 30px;
        }

        /* Preview*/
        /*Object Match*/
        .scanner-app-modal .object_match_form p.section-title {
            font-size: 18px;
            font-family: 'neue-montreal-medium' !important;
            color: #1D1D1D;
            margin-bottom: 16px;
        }

        .scanner-app-modal .object_match_form .upload_file {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
            width: 100%;
            height: 410px;
            vertical-align: middle;
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' stroke='%23CFCFCF' stroke-width='3' stroke-dasharray='6%2c 15' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            position: relative;
            transition: all 0.3s ease-in-out;
        }

        .scanner-app-modal .object_match_form .upload_file.focus_outline {
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' stroke='%23000000' stroke-width='3' stroke-dasharray='6%2c 15' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        }

        .scanner-app-modal .object_match_form .upload_file #object {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            cursor: pointer;
            opacity: 0;
        }

        .scanner-app-modal .object_match_form .upload_file .upload-btn {
            margin-top: 5px;
            padding: 10px 14px;
            background-color: #fff;
            border: 1px solid #CFCFCF;
            box-shadow: none;
            outline: none;
            font-size: 13px;
            font-family: 'neue-montreal-regular' !important;
            color: #1D1D1D;
        }

        .scanner-app-modal .object_match_form .upload_file p {
            width: 226px;
            margin: 0px auto;
            font-size: 18px;
            font-family: 'neue-montreal-medium' !important;
            color: #1D1D1D;
        }

        .scanner-app-modal .object_match_form .upload_view {
            display: block;
            width: 100%;
            min-width: 410px;
            height: 410px;
            background-color: #E8E8E8;
            border-radius: 6px;
        }

        .scanner-app-modal .object_match_form .upload_view .upload_view_img_wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            transition: all 0.3s ease-in-out;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .scanner-app-modal .object_match_form .upload_view .upload_view_img_wrapper > img {
            margin: 0px auto;
            width: auto;
            height: 100%;
        }

        .scanner-app-modal .object_match_form .upload_view .upload_view_img_wrapper .upload_view_controls {
            position: absolute;
            right: 16px;
            top: 16px;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            gap: 16px;
        }

        .scanner-app-modal .object_match_form .upload_view .upload_view_img_wrapper .upload_view_controls > span {
            width: 40px;
            height: 40px;
            border-radius: 40px;
            cursor: pointer;
        }

        .scanner-app-modal .object_match_form .reference_view {
            display: block;
            width: 210px;
            height: 210px;
        }

        .scanner-app-modal .object_match_form .reference_view > img {
            display: block;
            max-height: 100%;
            margin: 0px auto;
        }

        .scanner-app-modal .object_match_form .matching_p {
            margin-top: 16px;
            margin-bottom: 0px;
            font-size: 18px;
            font-family: 'neue-montreal-medium' !important;
            color: #1D1D1D;

            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 8px;
        }

        /*Object Match*/
        /*Confirm Modals*/
        .confirm-modal .modal-header {
            border: 0px;
        }

        .confirm-modal-void .modal-header {
            border-bottom: 1px solid #CFCFCF;
            margin-bottom: 24px;
        }

        .confirm-modal .modal-footer {
            border: 0px;
            gap: 16px;
            padding: 24px 32px !important;
        }

        .confirm-modal p {
            font-size: 14px;
            font-family: 'neue-montreal-regular' !important;
            color: #1D1D1D;
            margin: 0px;
        }

        .confirm-modal .modal-body {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }


        /*Confirm Modals*/
        /*Inventory and Authenticity Forms*/
        .label_forms .heading_18_black {
            font-family: "neue-montreal-medium";
            font-size: 18px;
            font-weight: 500;
            line-height: 20px;
            color: #1D1D1D;
        }

        .label_forms .column-gap {
            margin-bottom: 32px !important;
        }

        .label_forms .row-margin {
            margin-top: 16px !important;
        }

        .label_forms .row-margin > div {
            margin-top: 0px !important;
        }

        .label_forms #envelope_code,
        .label_forms #label_code {
            font-family: "neue-montreal-medium";
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            color: #B5B5B5;
        }

        .label_forms .helper-span {
            padding: 0px;
            margin: 0px;
            height: 34px;
        }

        .label_forms .helper-span > span {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 6px;
        }


        #multi_preview_default {
            display: block;
            width: 100%;
            margin-top: 16px;
            margin-bottom: 16px;
        }

        #multi_preview_default .jFiler {
            width: 100%;
        }

        #multi_preview_default .jFiler-input-dragDrop {
            width: 100%;
            background-color: #fff !important;
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' stroke='%23CFCFCF' stroke-width='3' stroke-dasharray='6%2c 15' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            border: 0px !important;
            border-radius: 4px;
        }

        #multi_preview_alt .jFiler.jFiler-theme-dragdropbox {
            flex: 0 0 32.00%;
            height: 173px;
            position: relative;
            border-radius: 4px;
            position: relative;
        }

        #multi_preview {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
        }

        #multi_preview .multi_preview_ex_list,
        #multi_preview .jFiler.jFiler-theme-dragdropbox,
        #multi_preview .jFiler-items.jFiler-row {
            flex: 0 0 32.00%;
            height: 173px;
            position: relative;
            border-radius: 4px;
            position: relative;
        }

        #multi_preview .jFiler-items.jFiler-row > img {
            display: block;
            height: 100%;
            width: 100%;
            border-radius: 4px;
        }

        #multi_preview .jFiler-items.jFiler-row > a {
            position: absolute;
            top: 16px;
            right: 16px;
            height: 40px;
            width: 40px;
            background-color: #000;
            border-radius: 40px;
        }

        #multi_preview .jFiler-items.jFiler-row .single-box {
            width: 100%;
        }

        /*Inventory and Authenticity Forms*/

        .container-fluid.extended {
            margin-bottom: 24px;
        }

        .container-fluid.extended .form-check {
            padding-left: 7px !important;
        }

        .container-fluid.extended .form-check-label {
            color: #1D1D1D;
            font-family: "neue-montreal-medium";
            font-size: 24px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
            padding: 0px;
        }

        .container-fluid.extended .child_label_extended {
            color: #1D1D1D;
            font-family: "neue-montreal-medium";
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
            padding: 0px;
            cursor: pointer;
        }

        .personal-info > li span.child_label_extended {
            justify-content: flex-end;
        }

        .label_step_notify {
            height: 48px;
            background-color: #FFFBEB;
            padding: 0px 16px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 18px;
            border-bottom: 1px solid rgba(29, 29, 29, 0.20);
        }

        .label_step_notify p {
            color: #D97706;
            font-family: "neue-montreal-medium";
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
            padding: 0;
            margin: 0;
        }

        .label_step_notify span {
            margin-left: auto;
        }

        .label_step_notify span > img {
            cursor: pointer;
        }

        .toast {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .cropper-view-box {
            outline: 2px solid #fff !important;
            outline-color: #fff !important;
            border-radius: 2px !important;
        }

        .cropper-point {
            background-color: #888;
        }

        button.btn.cancel-btn {
            transition-all: 0.3s ease-in-out;
        }

        button.cancel-btn.active-btn {
            background-color: #E8E8E8 !important;
            border: 1px solid #CFCFCF !important;
        }

        .jq-toast-wrap.top-right {
            width: auto !important;
            top: 60px !important;
            /* top: 200px !important; */
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        }

        .jq-toast-wrap.top-right h4 {
            color: #1D1D1D;
            font-family: "neue-montreal-medium";
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
        }

        .jq-toast-wrap.top-right .jq-toast-single {
            margin: 0px !important;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 16px;
            padding: 13px 16px !important;
        }

        .jq-toast-wrap.top-right .jq-toast-single img {
            margin-right: 16px;
        }

        .jFiler-input-text h3 {
            color: #1D1D1D;
            font-family: "neue-montreal-medium";
            font-size: 18px;
            font-style: normal;
            font-weight: 500;
        }

        .jFiler-input-text > span,
        .jFiler-input-choose-btn.btn-custom.blue-light {
            color: #1D1D1D;
            font-family: "neue-montreal-regular";
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
        }

        .jFiler-input-choose-btn.btn-custom.blue-light {
            border: 1px solid #CFCFCF;
        }

        span.labelStatus {
            padding: 0px 5px;
            color: #B5B5B5;
            font-family: "neue-montreal-regular";
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
        }

        ul.label-li > li {
            color: #1D1D1D;
            font-family: "neue-montreal-regular";
            font-size: 14px;
            padding-bottom: 10px;
        }

        .void_form textarea::placeholder {
            color: #CFCFCF !important;
        }

        .text-wrapper {
            position: relative;
            transition: all 0.3s ease-in-out;
        }

        .text-wrapper > span {
            position: absolute;
            right: 6px;
            top: 6px;
            cursor: pointer;
            display: none;
        }

        .text-wrapper > span.show {
            display: block;
        }

        #object_rotate {
            border: 1px solid #CFCFCF !important;
        }

        #object_crop {
            border: 1px solid #CFCFCF !important;
        }

        .scanner_form .upload-btn-wrapper {
            margin-bottom: 0px !important;
        }

        /*        An aditional shadow screen for all confirm popups*/
        #void_modal_confirm,
        #remove_label_modal_confirm,
        #remove_image_modal_confirm,
        #remove_image_modal_confirm_alt
        #label_processing_modal_confirm,
        #image_match_modal_confirm,
        #edit_modal_confirm,
        #discard_modal_confirm,
        #delete_modal_confirm,
        #delete_modal_confirm_alt {
            background-color: #0000004a !important;
        }
        .cropper-modal{
            background-color: #CFCFCF!important;
        }
    </style>
@endsection
@section('content')

    <div class="pages asign-request">

        <section class="alt-header" id="view-header">
            @include('pages.protect_request.components.header')
        </section>
        @include('pages.protect_request.components.rejection-popup')

        <section class="alt-section">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                            type="button" role="tab" aria-controls="nav-home" aria-selected="true">REQUEST
                    </button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                            type="button" role="tab" aria-controls="nav-profile" aria-selected="false">PROVENANCE
                    </button>
                    <button class="nav-link" id="nav-inspection-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-inspection"
                            type="button" role="tab" aria-controls="nav-inspection" aria-selected="false">Inspection
                    </button>
                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact"
                            type="button" role="tab" aria-controls="nav-contact" aria-selected="false">OBJECT DETAILS
                    </button>
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                     tabindex="0">
                    @include('pages.protect_request.tabs.request')
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                     tabindex="0">
                    @include('pages.protect_request.tabs.provenance')
                </div>
                <div class="tab-pane fade" id="nav-inspection" role="tabpanel" aria-labelledby="nav-inspection-tab"
                     tabindex="0">
                    @include('pages.protect_request.tabs.inspection')
                </div>
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab"
                     tabindex="0">
                    @include('pages.protect_request.tabs.object-detail')
                </div>
            </div>

        </section>
        {{-- @php
        echo "
        <pre>";
        print_r($data);
        echo "</pre>";
        @endphp --}}
        @include('pages.protect_request.scanner.scanner_popup')
    </div>

    {{-- Confirm Dialogs --}}
    @include('pages.protect_request.scanner.confirms.remove_image')
    @include('pages.protect_request.scanner.confirms.remove_image_alt')
    @include('pages.protect_request.scanner.confirms.discard_changes')
    @include('pages.protect_request.scanner.confirms.image_match_results')
    @include('pages.protect_request.scanner.confirms.void_label')
    @include('pages.protect_request.scanner.confirms.labelling_process')
    @include('pages.protect_request.scanner.confirms.exit_labelling')
    @include('pages.protect_request.scanner.confirms.delete_label')
    @include('pages.protect_request.scanner.confirms.delete_label_alt')
    {{-- Confirm Dialogs --}}
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.viewbox.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.uploader.min.js') }}"></script>
    <script src="{{ asset('js/rcrop.min.js') }}"></script>
    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
    {{-- Scanner App --}}
    <script src="{{ asset('js/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('js/cropper_latest.js') }}"></script>
    <script src="{{ asset('js/jquery-cropper.js') }}"></script>
    <script src="{{ asset('js/jquery.filer.js') }}"></script>
    <script src="{{ asset('js/protect_request/jquery.form.js') }}"></script>
    <script src="{{ asset('js/protect_request/scanner_app.js') }}"></script>


    <script type="text/javascript">
        let config = new Object();
        config.links = new Object;
        config.links.step = "{{ route('protect-request.scanner-app-step') }}";
        config.links.save = "{{ route('protect-request.scanner-app-save') }}";
        config.links.upload = "{{ route('protect-request.scanner-app-upload') }}";
        config.links.delete = "{{ route('protect-request.scanner-app-delete') }}";
        config.links.matching = "{{ route('protect-request.scanner-app-matching-value') }}";
        config.links.envelopeValidator = "{{ route('protect-request.validate-envelope') }}";
        config.links.labelValidator = "{{ route('protect-request.validate-label') }}";
        config.links.voidSave = "{{ route('protect-request.void-save') }}";
        config.links.removeImage = "{{ route('protect-request.remove-label-image') }}";
        config.links.extendedEdit = "{{ route('protect-request.image-edit-extended') }}";
        config.request_id = "{{ $data['id'] }}";
        config.current_step = "{{ $data['current_step'] }}";
        config.editIcon = "{{ asset('icons/edit_ic.svg') }}";
        config.deleteIcon = "{{ asset('icons/delete_ic.svg') }}";
        config.successIcon = "{{ asset('icons/success.svg') }}";

        new ScannerApp(config);
    </script>
    <script>
        $(document).ready(function () {
            $("#del-pop-ctr").on("contextmenu", function (e) {
                return false;
            });

            $("#container").on("contextmenu", function (e) {
                return false;
            });

        });

        let status = '{{ $data['status']['id'] }}';

        $('#team_form select').each(function () {
            $(this).select2({
                width: 'resolve',
                theme: "material",
                tags: false,
                minimumResultsForSearch: Infinity,
                placeholder: $(this).attr('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
                dropdownCssClass: 'removeSelect',
            });
        });

        $('select[name="inspection_time"], select[name="visit_time"]').on('select2:open', function (e) {
            $(this).closest('div').find('.select2-selection__placeholder').text('Select your prefered time');
        }).on('select2:close', function (e) {
            if ($(this).val() === '') {
                $(this).closest('div').find('.select2-selection__placeholder').text('Select time');
            }
        });


        $('.colorSelect').on('select2:open', function (e) {
            var val = $(this).val();
            var placeholder = $(this).attr('data-openplacehoder');
            if (val.length)
                placeholder = "";
            $(this).closest('span').find('textarea').attr('placeholder', placeholder);
        }).on('select2:close', function (e) {
            var val = $(this).val();
            var placeholder = $(this).attr('data-hideplacehoder');
            if (val.length)
                placeholder = "";
            $(this).closest('span').find('textarea').attr('placeholder', placeholder);
        });


        $(".select2Box").each(function () {
            var placeholder = $(this).attr('data-placeholder');
            var parent = $(this).closest('form').attr('id');
            $(this).select2({
                placeholder: placeholder,
                minimumResultsForSearch: 10,
                dropdownParent: $('#' + parent)
            });
        });

        $('.select2Box1').select2({
            minimumResultsForSearch: 10
        });

        $("#inspection_date").datepicker({
            dateFormat: "DD, d M, yy",
            minDate: 0
        });

        $("#visit_date").datepicker({
            dateFormat: "DD, d M, yy",
            minDate: 0
        });

        refreshDate();

        //Lightbox preview
        $('.view_box').viewbox({
            setTitle: false,
            margin: 40,
        });

        $('.view_box_1').viewbox({
            setTitle: false,
            margin: 40,
        });

        $('.artwork_thumbs img').on({
            click: function () {
                let thumbnailURL = $(this).attr('src');
                $('.artwork_preview img').fadeOut(200, function () {
                    $(this).attr('src', thumbnailURL);
                }).fadeIn(200);
            }
        });

        let rolesData = {
            authenticator_ids: @json($roles['authenticator']),
            conservator_ids: @json($roles['conservator']),
            field_agent_ids: @json($roles['field_agent']),
            service_provider_ids: @json($roles['service_provider']),
        }

        function check2Name(name) {
            let selectBox = $('select[name="' + name + '"]');
            if (selectBox.length === 0) return;
            let count = $('select[name="' + name + '"] option:selected').length;
            let selected = selectBox.val();
            let tmpSelect = rolesData[name];
            if (tmpSelect === undefined)
                return;

            selectBox.html('');

            if (name === 'field_agent_ids')
                name = 'Field Agent';
            else if (name === 'service_provider_ids')
                name = 'Service Provider';
            else {
                name = name.replace('_ids', '');
                name = name.charAt(0).toUpperCase() + name.slice(1);
            }

            if (count >= 2)
                selectBox.append(
                    '<optgroup class="optgroup-text" label="Maximum selection reached.Remove someone to make changes"></optgroup>'
                );
            else
                selectBox.append('<optgroup class="optgroup-text" label="You can add 1 primary and 1 secondary ' + name +
                    '"></optgroup>');

            tmpSelect.forEach(function (item) {
                if (selected.includes(item.id.toString())) {
                    selectBox.append('<option  value="' + item.id + '" selected>' + item.name + '</option>');
                } else if (count < 2) {
                    selectBox.append('<option  value="' + item.id + '">' + item.name + '</option>');
                }
            });
        }

        for (let key in rolesData) {
            check2Name(key);
        }

        $('#team_form').on('keyup change paste', 'input, select', function (e) {
            e.preventDefault();

            let _this = $(this);
            let value = _this.val();
            let name = _this.attr('name');
            check2Name(name);
            if (name === undefined) {
                name = _this.closest('span').find('input').attr('name');
                if (name === undefined)
                    return;
            }
            refreshDate();
            if (_this.hasClass('datepicker')) {
                value = changeDateFormat(value);
            }

            if (value == null || value == undefined || value == '') {
                value = '';
            }

            var data = {
                [name]: value
            }
            updateTeam(data, _this);
        });

        function updateTeam(data, _this) {
            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/team",
                type: "PATCH",
                data: data,
                success: function (response) {
                    $('#view-header').html(response.data.header);
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                    _this.val('');
                },
                complete: function (response) {
                }
            });
        }


        function refreshDate() {
            var currentDate = $("#inspection_date").datepicker("getDate");
            if (currentDate != null) {
                $("#visit_date").datepicker("option", "minDate", currentDate);
            }

            var visitDate = $("#visit_date").datepicker("getDate");

            if (visitDate != null) {
                if (currentDate != null && currentDate.getTime() === visitDate.getTime()) {

                    var inspection_time = $('select[name="inspection_time"]').val();

                    if (inspection_time) {
                        var disabled = true;
                        $('select[name="visit_time"] option').each(function () {
                            if ($(this).val() === inspection_time) {
                                $(this).prop('disabled', true);
                                disabled = false
                            } else {
                                $(this).prop('disabled', disabled);
                            }
                        });

                    }
                } else {
                    $('select[name="visit_time"] option').each(function () {
                        $(this).prop('disabled', false);
                    });
                }
            }


        }


        $(document).on('change', '.objectDetails, .provenance', function () {
            let tr = $(this).closest('tr');
            if ($(this).hasClass('objectDetails'))
                tr = $(this).closest('div');
            let data = {
                verify: $(this).is(':checked'),
                type_id: tr.attr('data-value'),
                type: tr.attr('data-type'),
            }
            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/verify",
                type: "PATCH",
                data: data,
                success: function (response) {
                    $('#view-header').html(response.data.header);
                    $('#objectLabels').html(response.data['object-label']);
                    asignProtectVerify();
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                },
                complete: function (response) {
                }
            });
        });


        $('#location-form').on('submit', function (e) {
            e.preventDefault();
            let data = $(this).serialize();
            let button = $(this).find('button[type="submit"]');
            button.attr('disabled', true).text('Loading...');
            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/location",
                type: "POST",
                data: data,
                success: function (response) {
                    $('#locationAccordion').append(response.data);
                    $('#addLocationModal').modal('hide');
                    $('#location-form').trigger('reset');
                    $('#location-form').find('button[type="submit"]').prop('disabled', true)
                    $('select[name="country_id"] option[value="102"]').prop('selected', true);
                    $('select[name="country_id"]').trigger('change');
                    asignProtectVerify();
                    toastr.success(response.message);
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                },
                complete: function (response) {
                    button.attr('disabled', false).text('Save');
                }
            });
        });

        $("#drag_upload").uploader({
            ajaxConfig: {
                paramsBuilder: function (uploaderFile) {
                    let form = new FormData();
                    form.append("file", uploaderFile.file)
                    return form
                },
                ajaxRequester: function (config, uploaderFile, progressCallback, successCallback, errorCallback) {

                    $('#about-form button[type="submit"]').attr('disabled', true);

                    $.ajax({
                        url: baseUrl + '/protect-request/file-upload',
                        contentType: false,
                        processData: false,
                        method: 'POST',
                        data: config.paramsBuilder(uploaderFile),
                        success: function (response) {
                            successCallback(response)
                        },
                        error: function (response) {
                            console.error("Error", response)
                            errorCallback("Error")
                        },
                        complete: function (response) {
                            $('#about-form button[type="submit"]').attr('disabled', false).text(
                                'Save');
                        },
                        xhr: function () {
                            let xhr = new XMLHttpRequest();
                            xhr.upload.addEventListener('progress', function (e) {
                                let progressRate = (e.loaded / e.total) * 100;
                                progressCallback(progressRate)
                            })
                            return xhr;
                        }
                    })
                },
                responseConverter: function (uploaderFile, response) {
                    $('input[name="cover_image"]').val(response.data.name);
                    return {
                        url: response.data.url,
                        name: response.data.name,
                    }
                },
            },
        });

        $('#component-location-form, #signature-form, #measurement-form, #medium-form, #about-form').on('submit', function (
            e) {
            e.preventDefault();
            let button = $(this).find('button[type="submit"]');
            button.attr('disabled', true).text('Loading...');

            let component_id = $('input[name="component_id"]').val();
            let data = new FormData($(this)[0]);
            data.append('component_id', component_id);

            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/component",
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                success: function (response) {
                    let id = response.data.id;
                    $('input[name="component_id"]').val(id);
                    if (component_id != '')
                        $('#components').find('div[data-component-id="' + id + '"]').remove();

                    $('#components').append(response.data.data);
                    asignProtectVerify();
                    toastr.success(response.message);

                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                },
                complete: function (response) {
                    button.attr('disabled', false).text('Save');
                }
            });
        });

        $('#addComponentModal').on('hidden.bs.modal', function () {
            $('input[name="component_id"]').val('');
            $('#component-location-form, #signature-form, #measurement-form, #medium-form, #about-form').trigger(
                'reset');
            $('.singleUploadImage').find('.file-delete').trigger('click');

            $('#measurement-form select, #medium-form select, #signature-form select').val('').trigger('change');

        });

        $(document).on('click', '#commentBtn', function (e) {
            e.preventDefault();
            saveComment();
        });


        // $('textarea[name="message"]').keydown(function (e) {
        //     if (e.keyCode === 13) {
        //         if (e.ctrlKey) {
        //             e.preventDefault();
        //             var content = this.value;
        //             var caret = this.selectionStart;
        //             this.value = content.substring(0, caret) + "\n" + content.substring(this.selectionEnd, content
        //                 .length);
        //             this.selectionStart = caret + 1;
        //             this.selectionEnd = caret + 1;
        //         } else {
        //             e.preventDefault();
        //             saveComment();
        //         }
        //     }
        // });

        function saveComment() {
            let message = $('textarea[name="message"]');
            if (message.val() === '') return;
            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/message",
                type: "POST",
                data: {
                    message: message.val()
                },
                success: function (response) {
                    $('#activity-log').prepend(response.data);
                    message.val('');
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                },
                complete: function (response) {
                }
            });
        }


        $('#is_signature').on('change', function () {
            let signature = $(this).val();
            if (signature == 1) {
                $('#signatureDiv').show();
            } else {
                $('#signatureDiv').hide();
            }
        });

        $('#is_inscription').on('change', function () {
            let inscription = $(this).val();
            if (inscription == 1) {
                $('#inscriptionDiv').show();
            } else {
                $('#inscriptionDiv').hide();
            }
        });

        // Rejection script

        $('#rejectProtectModal').on('hidden.bs.modal', function () {
            $('#reviewer_name_div').hide().html('');
            $('#rejection-form').show();


            $('#supervisor_id').val('').trigger('change');
        });
        disabledButton('rejection-form');

        $('#rejection-form').on('submit', function (e) {
            e.preventDefault();
            let data = $(this).serialize();
            let button = $(this).find('button[type="submit"]');
            button.attr('disabled', true).text('Loading...');
            let name = $('#supervisor_id option:selected').text();
            let text = "Rejection successfully shared with " + name;
            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/rejection",
                type: "POST",
                data: data,
                success: function (response) {
                    $('#rejection-form').hide().trigger('reset');
                    $('#reviewer_name_div').show().html(text);
                    $('#view-header').html(response.data.header);
                    $('#activity-log').prepend(response.data.activity);
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                },
                complete: function (response) {
                    button.attr('disabled', false).text('Send for Review');
                }
            });
        });

        $('#reject-approve-form').on('submit', function (e) {
            e.preventDefault();

            let data = $(this).serialize();
            let button = $(this).find('button[type="submit"]');
            button.attr('disabled', true).text('Loading...');

            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/rejection-approve",
                type: "POST",
                data: data,
                success: function (response) {
                    $('#reject-approve-form').trigger('reset');
                    $('#view-header').html(response.data.header);
                    $('#activity-log').prepend(response.data.activity);
                    $('#rejectApproveModal').modal('hide');
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                },
                complete: function (response) {
                    button.attr('disabled', false).text('Send for Customer');
                }
            });
        });

        $('#override-rejection-form').on('submit', function (e) {
            e.preventDefault();

            let data = $(this).serialize();
            let button = $(this).find('button[type="submit"]');
            button.attr('disabled', true).text('Loading...');

            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/rejection-override",
                type: "POST",
                data: data,
                success: function (response) {
                    $('#view-header').html(response.data.header);
                    $('#activity-log').prepend(response.data.activity);
                    $('#rejectOverrideModal').modal('hide');
                },
                error: function (xhr) {
                },
                complete: function (response) {
                    button.attr('disabled', false).text('Override');
                }
            });
        });

        $('#approve-form').on('submit', function (e) {
            e.preventDefault();

            let data = $(this).serialize();
            let button = $(this).find('button[type="submit"]');
            button.attr('disabled', true).text('Loading...');

            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/approve",
                type: "POST",
                data: data,
                success: function (response) {
                    $('#view-header').html(response.data.header);
                    $('#activity-log').prepend(response.data.activity);
                    $('#approveModal').modal('hide');

                    changeStatus(response.data.status)

                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                },
                complete: function (response) {
                    button.attr('disabled', false).text('Yes');
                }
            });
        });

        $('#edit-icon').on('click', function () {
            showObjectDetails('view');
        });

        $('#save-icon').on('click', function () {
            showObjectDetails('save');
        });

        function showObjectDetails(type = 'view') {

            console.log("type .................");
            let data = {};
            let pageForm = $("#objectDetailForm");
            let link = "/view-object";
            let method = 'get';
            if (type === 'save') {
                link = "/save-object";
                method = "POST";
                data = $('#objectDetailForm').serialize();
            }

            pageForm.toggleClass("open-editable close-editable");

            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}" + link,
                type: method,
                data: data,
                success: function (response) {
                    $('#aboutObject').html(response.data.about);
                    $('#mediumObject').html(response.data.medium);
                    $('#signatureObject').html(response.data.signature);
                    if (type === 'view') {
                        $('#edit-icon').hide();
                        $('#save-icon').show();

                        $(".select2Box").each(function () {
                            var placeholder = $(this).attr('data-placeholder');
                            var parent = $(this).closest('form').attr('id');
                            $(this).select2({
                                placeholder: placeholder,
                                minimumResultsForSearch: 10,
                                dropdownParent: $('#' + parent)
                            });
                        });


                    } else {
                        $('#edit-icon').show();
                        $('#save-icon').hide();
                    }
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                },
                complete: function (response) {
                }
            });
        }

        $('select[name="country_id"]').on('change', function () {
            var country = $(this).val();
            $.ajax({
                url: "{{ route('city.getState') }}",
                method: 'GET',
                data: {
                    country: country
                },
                success: function (response) {
                    if (response.states) {
                        $('select[name="state_id"]').empty().append(
                            '<option value="" selected>Choose State</option>');
                        $.each(response.states, function (key, state) {
                            $('select[name="state_id"]').append('<option value="' + state.id +
                                '">' + state.name + '</option>');
                        });
                    }
                },
                error: function (xhr) {
                    showErrorMessage(xhr);

                }
            });
        });

        function asignProtectVerify(show = true) {
            if (status === 'asign-protect') {
                let objectDetails = $('.objectDetails');
                let objectDetailsChecked = 0;
                let objectDetailsTotal = objectDetails.length;
                objectDetails.each(function () {
                    if ($(this).is(':checked')) {
                        objectDetailsChecked++;
                    }
                });

                if (objectDetailsChecked === objectDetailsTotal) {
                    $('#approveBtn').attr('disabled', false);
                    $('#start_labelling').attr('disabled', false);
                    if (show)
                        showNotify();

                    let stLabel = $('#startLabel').attr('title', '')
                    if ($('#startLabel').is(':ui-tooltip')) {
                        stLabel.tooltip('dispose');
                    }
                } else {
                    $('#approveBtn').attr('disabled', true);
                    $('#start_labelling').attr('disabled', true);
                    var titleLabel = $.trim($('.startLabel').text()).toLowerCase();
                    $('#startLabel').attr('data-bs-original-title', 'Verify all object details to ' + titleLabel)
                    $('#startLabel').tooltip({
                        title: 'Verify all object details to ' + titleLabel,
                        placement: 'bottom'
                    });
                }

            }
        }

        function showNotify() {
            $.toast({
                allowToastClose: false,
                hideAfter: 1000,
                position: 'top-right',
                bgColor: '#ffffff',
                textColor: '#1D1D1D',
                textAlign: 'left',
                icon: false,
                text: "<h4><img src=" + config.successIcon + " width='15'/> Verified! Start Labelling<h4>"
            });
        }


       asignProtectVerify(false);

        var bs_modal = $('#addImageModal');
        var bs_modal_1 = $('#addImageModal-1');
        var bs_modal_2 = $('#addImageModal-2');
        var cropbox = document.getElementById('rc_img');
        var previewbox = document.getElementById('rc_preview');
        var cropthumb, reader, file;

        // draging and drop script
        document.querySelectorAll(".object-image").forEach((inputElement) => {
            const dropZoneElement = inputElement.closest(".drop-zone");

            dropZoneElement.addEventListener("click", (e) => {
                inputElement.click();
            });

            inputElement.addEventListener("change", (e) => {
                if (inputElement.files.length) {
                    updateThumbnail(dropZoneElement, inputElement.files[0]);
                }
            });

            dropZoneElement.addEventListener("dragover", (e) => {
                e.preventDefault();
                dropZoneElement.classList.add("drop-zone--over");
            });

            ["dragleave", "dragend"].forEach((type) => {
                dropZoneElement.addEventListener(type, (e) => {
                    dropZoneElement.classList.remove("drop-zone--over");
                });
            });

            dropZoneElement.addEventListener("drop", (e) => {
                e.preventDefault();

                if (e.dataTransfer.files.length) {
                    inputElement.files = e.dataTransfer.files;
                    updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
                }

                dropZoneElement.classList.remove("drop-zone--over");
            });
        });

        $(document).on('change', '#additionalImage', function (e) {
            var files = e.target.files;
            var done = function (url) {
                cropbox.src = url;
                previewbox.src = url;
                // bs_modal.modal('show');
                bs_modal_1.modal('show');
                document.getElementById('addImageModal').style.display = 'none';
            };
            if (files && files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                    e.target.value = ''
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                    e.target.value = ''
                }
            }
        });

        bs_modal_1.on('shown.bs.modal', function () {
            var $image2 = $('#rc_img'),
                $update = $('#update'),
                inputs = {
                    x: $('#x'),
                    y: $('#y'),
                    width: $('#width'),
                    height: $('#height')
                },
                fill = function () {
                    var values = $image2.rcrop('getValues');
                    for (var coord in inputs) {
                        inputs[coord].val(values[coord]);
                    }

                    var srcResized = $image2.rcrop('getDataURL');
                    $('#rc_preview').attr("src", srcResized);
                };

            $image2.rcrop({
                preview: {
                    display: true,
                    wrapper: '#rc_preview'
                }
            });

            $image2.on('rcrop-ready rcrop-change rcrop-changed', fill);

            $update.click(function () {
                $image2.rcrop('resize', inputs.width.val(), inputs.height.val(), inputs.x.val(), inputs.y
                    .val());
                fill();
            });
        }).on('hidden.bs.modal', function () {
            $("#addImageModal").modal("hide");
            $("#addImageModal-1").modal("hide");
            document.getElementById('addImageModal').style.display = 'none';
            document.getElementById('addImageModal-1').style.display = 'none';
            $('.add-image-before').empty().html("<div class='drop-zone__prompt ff-medium'>Drop files to upload</div><div class='drop-zone__prompt-2 fs-14'>Or</div><div class='drop-zone__prompt-3 mt-3'><span> Select Files</span></div>");
            $('.drop-zone__thumb').remove();
            $('#rc_preview').attr("src", "");
            $('#rc_img').attr("src", "");
            $('#rc_img').rcrop('destroy');
        });

        // $('#imageCropFormStep').on('submit', function (e) {
        //     e.preventDefault();
        //     if (document.getElementById('imageCropFormStep')) {
        //         document.getElementById('imageCropForm').style.display = 'block';
        //         document.getElementById('imageCropFormStep').style.display = 'none';
        //     }
        // });

        // document.getElementById('cancel').onclick = function(){
        //     console.log('hide-4324');
        //     $("#addImageModal").modal("hide");
        //     $("#addImageModal-1").modal("hide");
        //     document.getElementById('additionalImage').value= "";
        //     document.getElementById('addImageModal').style.display = 'none';
        //     document.getElementById('addImageModal-1').style.display = 'none';
        // }

        $('#imageCropForm').on('submit', function (e) {
            e.preventDefault();

            let data = new FormData();
            let base64String = $('#rc_img').rcrop('getDataURL');
            let mimeType = getMimeTypeFromDataURL(base64String);

            var imageBlob = base64ToBlob(base64String, mimeType);
            data.append("file", imageBlob, "image.png");

            let button = $(this).find('button[type="submit"]');
            button.attr('disabled', true).text('Loading...');

            $.ajax({
                url: baseUrl + '/protect-request/{{ $data['id'] }}/file-upload-crop',
                contentType: false,
                processData: false,
                method: 'POST',
                data: data,
                success: function (response) {
                    $('#addImageModal').modal('hide');
                    let url = response.data.url;

                    $('.image-preview-1').find('.upload-btn-wrapper').before('<a href="' + url +
                        '" class="view_box" title=""><img src="' + url +
                        '" alt="" class="img-fluid"> <div class="image-modal"><h6 class="image-lable">Additional Image</h6></div></a>');
                    $(".view_box_1").unbind().removeData();
                    $('.view_box_1').viewbox({
                        setTitle: false,
                        margin: 40,
                    });
                    $("#addImageModal").modal("hide");
                    $("#addImageModal-1").modal("hide");
                    document.getElementById('addImageModal').style.display = 'none';
                    document.getElementById('addImageModal-1').style.display = 'none';
                },
                error: function (response) {
                    console.error("Error", response)
                },
                complete: function (response) {
                    button.attr('disabled', false).text('Save');
                }
            });

        });

        function getMimeTypeFromDataURL(dataURL) {
            var mimeTypeMatch = /^data:([^;]+)(;base64)?,/.exec(dataURL);
            if (mimeTypeMatch) {
                return mimeTypeMatch[1];
            }
            return null;
        }

        function base64ToBlob(base64, mimeType) {
            var byteCharacters = atob(base64.split(',')[1]);
            var byteArrays = [];

            for (var offset = 0; offset < byteCharacters.length; offset += 512) {
                var slice = byteCharacters.slice(offset, offset + 512);
                var byteNumbers = new Array(slice.length);
                for (var i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray);
            }

            var blob = new Blob(byteArrays, {
                type: mimeType
            });
            return blob;
        }

        disabledButton('location-form');

        function changeStatus(status) {
            if (status !== 'authentication') {
                $('#team_form').find('input, select').attr('disabled', true);
            }
            if (status === 'inspection') {
                let teamForm = $('#team_form');
                let datepicker = teamForm.find('.datepicker');
                let timepicker = teamForm.find('.timepicker');
                datepicker.removeClass('disabled');
                timepicker.removeClass('disabled');
            }

        }

        // Inspection Tab - Object Condition
        /* if($(".inspectionq_omiu input[name$='objectMatchImageUpload']:checkbox:checked").length > 0) {
            $(".inspectionq_omiu_reason_divhide").hide();
        } */

        $(".inspectionq_omiu input[name$='objectMatchImageUpload']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_omiu_divhide_no").hide();
                $(".inspectionq_omiu_divhide").show();
                $(".inspectionq_apc").show();
                $(".inspectionq_apc_divhide").show();

                if ($(".inspectionq_apc input[name$='asignProtectCondition']").is(':checked') && $(".inspectionq_apc input[name$='asignProtectCondition']:checked").val() != 0 && $(".inspectionq_ss input[name$='surfaceSuitable']").is(':checked') && $(".inspectionq_ss input[name$='surfaceSuitable']:checked").val() != 0) {
                    $(".inspectionq_ss_divhide").show();
                } else {
                    $(".inspectionq_ss_divhide").hide();
                }
            } else {
                $(".inspectionq_omiu_divhide").hide();
                $(".inspectionq_omiu_divhide select[name$='objectCondition']").val('').trigger("change");
                $(".inspectionq_omiu_divhide input[name$='noticeableDamages']").prop('checked', false);
                $(".inspectionq_apc_divhide").hide();
                $(".inspectionq_apc_divhide input[name$='surfaceSuitable']").prop('checked', false);
                $(".inspectionq_apc").hide();
                $(".inspectionq_apc input[name$='asignProtectCondition']").prop('checked', false);
                $(".inspectionq_apc_divhide_no").hide();
                $(".inspectionq_apc_divhide_no select[name$='asignProtectConditionReason']").val('').trigger("change");
                $(".inspectionq_ss_divhide").hide();
                $(".inspectionq_ss_divhide input[name$='surfaceLabelApplied']").prop('checked', false);
                $(".inspectionq_ss_divhide_no").hide();
                $(".inspectionq_mf_divhide").hide();
                $(".inspectionq_mst_divhide").hide();
                $(".inspectionq_ms_divhide").hide();
                $(".inspectionq_omiu_divhide_no").show();
                $(".inspectionq_omiu_reason select[name$='objectMatchImageUploadReason']").val('').trigger("change");
            }
        });

        $(".inspectionq_omiu_reason select[name$='objectMatchImageUploadReason']").on('change', function (e) {

            if ($(".inspectionq_omiu_reason select[name$='objectMatchImageUploadReason']").val() == 15) {
                $(".inspectionq_omiu_reason_divhide").show();
            } else {
                $(".inspectionq_omiu_reason_divhide").hide();
                $(".inspectionq_omiu_reason_divhide textarea[name$='objectMatchImageUploadReasonNotes']").val('');
            }
        });

        $(".inspectionq_ond input[name$='noticeableDamages']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_ond_divhide").show();
            } else {
                $(".inspectionq_ond_divhide").hide();

                $(".inspectionq_ond_reason select[name$='objectNoticeableDamageReason']").val('').trigger("change");
                $(".inspectionq_ond_reason_notes_divhide textarea[name$='objectNoticeableDamageReasonNotes']").val('');
            }
        });

        $(".inspectionq_ond_reason select[name$='objectNoticeableDamageReason']").on('change', function (e) {
            $(".inspectionq_ond_reason_notes_divhide textarea[name$='objectNoticeableDamageReasonNotes']").val('');
            if ($(".inspectionq_ond_reason select[name$='objectNoticeableDamageReason']").val() == 22 || $(".inspectionq_ond_reason select[name$='objectNoticeableDamageReason']").val() == 26) {
                $(".inspectionq_ond_reason_notes_divhide").show();
            } else {
                $(".inspectionq_ond_reason_notes_divhide").hide();
            }

            if ($(".inspectionq_ond_reason select[name$='objectNoticeableDamageReason']").val() == 29) {
                $(".inspectionq_ond_reason_notes_divhide").show();
                $(".inspectionq_ond_reason_images_divhide").show();
            } else {
                $(".inspectionq_ond_reason_images_divhide").hide();
            }
        });

        $(".inspectionq_apc input[name$='asignProtectCondition']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_apc_divhide").show();

                $(".inspectionq_apc_divhide_no").hide();
                $(".inspectionq_apc_reason_notes_divhide").hide();
            } else {
                //$(".inspectionq_ss_divhide").hide();

                $(".inspectionq_apc_divhide_no").show();

                $(".inspectionq_apc_reason select[name$='asignProtectConditionReason']").val('').trigger("change");
                $(".inspectionq_apc_reason_notes_divhide textarea[name$='asignProtectConditionReasonNotes']").val('');
            }
        });

        $(".inspectionq_apc_reason select[name$='asignProtectConditionReason']").on('change', function (e) {
            if ($(".inspectionq_apc_reason select[name$='asignProtectConditionReason']").val() == 38) {
                $(".inspectionq_apc_reason_notes_divhide").show();
            } else {
                $(".inspectionq_apc_reason_notes_divhide").hide();
                $(".inspectionq_apc_reason_notes_divhide textarea[name$='asignProtectConditionReasonNotes']").val('');
            }
        });

        $(".inspectionq_ss input[name$='surfaceSuitable']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_ss_divhide").show();
                $(".inspectionq_ss_divhide_no").hide();

                $(".inspectionq_mf select[name$='materialFrame']").val('').trigger("change");
                $(".inspectionq_mst select[name$='materialStand']").val('').trigger("change");
                $(".inspectionq_ms select[name$='materialStretcher']").val('').trigger("change");
                $(".inspectionq_ss_divhide textarea[name$='objectAdditionalNotes']").val('');
            } else {
                $(".inspectionq_ss_divhide").hide();
                $(".inspectionq_ss_divhide input[name$='surfaceLabelApplied']").prop('checked', false);
                $(".inspectionq_ss_divhide_no").show();
                $(".inspectionq_ss_reason_images_divhide").show();

                $(".inspectionq_ss_reason select[name$='objectSurfaceSuitableReason']").val('').trigger("change");
                $(".inspectionq_ss_reason_notes_divhide textarea[name$='objectSurfaceSuitableReasonNotes']").val('');
                $(".inspectionq_ss_reason textarea[name$='objectAdditionalReasonNotes']").val('');
            }
        });

        $(".inspectionq_ss_divhide input[name$='surfaceLabelApplied']").on('click', function (e) {
            $(".inspectionq_ss_divhide .inspectionq_mf_divhide select, .inspectionq_ss_divhide .inspectionq_ms_divhide select, .inspectionq_ss_divhide .inspectionq_mst_divhide select").val('').trigger("change");
            $(".inspectionq_ss_divhide .inspectionq_mf_divhide textarea, .inspectionq_ss_divhide .inspectionq_ms_divhide textarea, .inspectionq_ss_divhide .inspectionq_mst_divhide textarea").val('');

            if ($(this).val() == 'Frame') {
                $(".inspectionq_mf_divhide").show();
            } else {
                $(".inspectionq_mf_divhide").hide();
            }

            if ($(this).val() == 'Stretcher') {
                $(".inspectionq_ms_divhide").show();
            } else {
                $(".inspectionq_ms_divhide").hide();
            }

            if ($(this).val() == 'Object Stand') {
                $(".inspectionq_mst_divhide").show();
            } else {
                $(".inspectionq_mst_divhide").hide();
            }
        });

        $(".inspectionq_mf select[name$='materialFrame']").on('change', function (e) {
            if ($(".inspectionq_mf select[name$='materialFrame']").val() == 12) {
                $(".inspectionq_mf_notes_divhide").show();
            } else {
                $(".inspectionq_mf_notes_divhide").hide();
                $(".inspectionq_mf_notes_divhide textarea[name$='materialFrameNotes']").val('');
            }
        });

        $(".inspectionq_mst select[name$='materialStand']").on('change', function (e) {
            if ($(".inspectionq_mst select[name$='materialStand']").val() == 25) {
                $(".inspectionq_mst_notes_divhide").show();
            } else {
                $(".inspectionq_mst_notes_divhide").hide();
                $(".inspectionq_mst_notes_divhide textarea[name$='materialStandNotes']").val('');
            }
        });

        $(".inspectionq_ms select[name$='materialStretcher']").on('change', function (e) {
            if ($(".inspectionq_ms select[name$='materialStretcher']").val() == 14) {
                $(".inspectionq_ms_notes_divhide").show();
            } else {
                $(".inspectionq_ms_notes_divhide").hide();
                $(".inspectionq_ms_notes_divhide textarea[name$='materialStretcherNotes']").val('');
            }
        });

        $(".inspectionq_ss_reason select[name$='objectSurfaceSuitableReason']").on('change', function (e) {
            if ($(this).val() == 47) {
                $(".inspectionq_ss_reason_notes_divhide").show();
            } else {
                $(".inspectionq_ss_reason_notes_divhide").hide();
                $(".inspectionq_ss_reason_notes_divhide textarea[name$='objectSurfaceSuitableReasonNotes']").val('');
                $(".inspectionq_ss_reason textarea[name$='objectAdditionalReasonNotes']").val('');
            }
        });

        // Inspection Tab - Site Condition
        $(".inspectionq_aps input[name$='adequatePhysicalSpace']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_aps_divhide").hide();

                $(".inspectionq_aps_reason select[name$='adequatePhysicalSpaceReason']").val('').trigger("change");
                $(".inspectionq_aps_reason_notes_divhide textarea[name$='adequatePhysicalSpaceReasonNotes']").val('');
                $(".inspectionq_aps_alternativespace input[name$='adequatePhysicalAlternativeSpace']").prop('checked', false);
                $(".inspectionq_aps_alternativespace_notes_divhide textarea[name$='adequatePhysicalAlternativespaceNotes']").val('');
            } else {
                $(".inspectionq_aps_divhide").show();
                $(".inspectionq_aps_alternativespace_divhide").show();
                $(".inspectionq_aps_alternativespace_notes_divhide").hide();
            }
        });

        $(".inspectionq_aps_reason select[name$='adequatePhysicalSpaceReason']").on('change', function (e) {
            if ($(this).val() == 8) {
                $(".inspectionq_aps_reason_notes_divhide").show();
            } else {
                $(".inspectionq_aps_reason_notes_divhide").hide();
                $(".inspectionq_aps_reason_notes_divhide textarea[name$='adequatePhysicalSpaceReasonNotes']").val('');
            }
        });

        $(".inspectionq_aps_alternativespace input[name$='adequatePhysicalAlternativeSpace']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_aps_alternativespace_notes_divhide").show();
            } else {
                $(".inspectionq_aps_alternativespace_notes_divhide").hide();
                $(".inspectionq_aps_alternativespace_notes_divhide textarea[name$='adequatePhysicalAlternativespaceNotes']").val('');
            }
        });

        $(".inspectionq_sw input[name$='smoothWorkflow']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_sw_divhide").hide();

                $(".inspectionq_sw_reason select[name$='smoothWorkflowReason']").val('').trigger("change");
                $(".inspectionq_sw_reason_notes_divhide textarea[name$='smoothWorkflowReasonNotes']").val('');
            } else {
                $(".inspectionq_sw_divhide").show();
            }
        });

        $(".inspectionq_sw_reason select[name$='smoothWorkflowReason']").on('change', function (e) {
            if ($(this).val() == 22) {
                $(".inspectionq_sw_reason_notes_divhide").show();
            } else {
                $(".inspectionq_sw_reason_notes_divhide").hide();
                $(".inspectionq_sw_reason_notes_divhide textarea[name$='smoothWorkflowReasonNotes']").val('');
            }
        });

        // Lighting Adequate
        $(".inspectionq_la input[name$='lightingAdequate']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_la_divhide").hide();

                $(".inspectionq_la_reason select[name$='lightingAdequateReason']").val('').trigger("change");
                $(".inspectionq_la_reason_notes_divhide textarea[name$='lightingAdequateReasonNotes']").val('');
                $(".inspectionq_la_alternativespace input[name$='lightingAdequateAlternativeSpace']").prop('checked', false);
                $(".inspectionq_la_alternativespace_notes_divhide textarea[name$='lightingAdequateAlternativespaceNotes']").val('');
            } else {
                $(".inspectionq_la_divhide").show();
                $(".inspectionq_la_alternativespace_divhide").show();
                $(".inspectionq_la_alternativespace_notes_divhide").hide();
            }
        });

        $(".inspectionq_la_reason select[name$='lightingAdequateReason']").on('change', function (e) {
            if ($(this).val() == 13) {
                $(".inspectionq_la_reason_notes_divhide").show();
            } else {
                $(".inspectionq_la_reason_notes_divhide").hide();
                $(".inspectionq_la_reason_notes_divhide textarea[name$='lightingAdequateReasonNotes']").val('');
            }
        });

        $(".inspectionq_la_alternativespace input[name$='lightingAdequateAlternativeSpace']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_la_alternativespace_notes_divhide").show();
            } else {
                $(".inspectionq_la_alternativespace_notes_divhide").hide();
                $(".inspectionq_la_alternativespace_notes_divhide textarea[name$='lightingAdequateAlternativespaceNotes']").val('');
            }
        });

        // Surrounding WorkSpace
        $(".inspectionq_swe input[name$='surroundingWorkSpace']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_swe_divhide").hide();

                $(".inspectionq_swe_reason select[name$='surroundingWorkSpaceReason']").val('').trigger("change");
                $(".inspectionq_swe_reason_notes_divhide textarea[name$='surroundingWorkSpaceReasonNotes']").val('');
                $(".inspectionq_swe_alternativespace input[name$='surroundingWorkSpaceAlternativeSpace']").prop('checked', false);
                $(".inspectionq_swe_alternativespace_notes_divhide textarea[name$='surroundingWorkSpaceAlternativespaceNotes']").val('');
            } else {
                $(".inspectionq_swe_divhide").show();
                $(".inspectionq_swe_alternativespace_divhide").show();
                $(".inspectionq_swe_alternativespace_notes_divhide").hide();
            }
        });

        $(".inspectionq_swe_reason select[name$='surroundingWorkSpaceReason']").on('change', function (e) {
            if ($(this).val() == 21) {
                $(".inspectionq_swe_reason_notes_divhide").show();
            } else {
                $(".inspectionq_swe_reason_notes_divhide").hide();
                $(".inspectionq_swe_reason_notes_divhide textarea[name$='surroundingWorkSpaceReasonNotes']").val('');
            }
        });

        $(".inspectionq_swe_alternativespace input[name$='surroundingWorkSpaceAlternativeSpace']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_swe_alternativespace_notes_divhide").show();
            } else {
                $(".inspectionq_swe_alternativespace_notes_divhide").hide();
                $(".inspectionq_swe_alternativespace_notes_divhide textarea[name$='surroundingWorkSpaceAlternativespaceNotes']").val('');
            }
        });

        $(".inspectionq_sp input[name$='safetyProtocols']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_sp_divhide").show();
                $(".inspectionq_sp_divhide_no").hide();
                $(".inspectionq_sp_divhide_no textarea[name$='observationSecurity']").val('');
            } else {
                $(".inspectionq_sp_divhide").hide();
                $(".inspectionq_sp_divhide textarea[name$='emergencyExit']").val('');
                $(".inspectionq_sp_divhide textarea[name$='securityRequirements']").val('');
                $(".inspectionq_sp_divhide_no").show();
            }
        });

        $(".inspectionq_laa input[name$='washroomAvailable']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_laa_divhide").show();
                $(".inspectionq_laa_divhide_no").hide();
                $(".inspectionq_laa_divhide_no textarea[name$='nearestWashroom']").val('');
            } else {
                $(".inspectionq_laa_divhide").hide();
                $(".inspectionq_laa_divhide textarea[name$='locatedAndAccessed']").val('');
                $(".inspectionq_laa_divhide_no").show();
            }
        });

        $(".inspectionq_nc input[name$='networkCoverage']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".inspectionq_nc_divhide_no").hide();
                $(".inspectionq_nc_divhide_no textarea[name$='alternateAvailableNetwork']").val('');
            } else {
                $(".inspectionq_laa_divhide").hide();
                $(".inspectionq_nc_divhide_no").show();
            }
        });

        //inspectionFormValidate();

        /* function inspectionFormValidate() {
            var $approve_btn = $('.asign-request .alt-header-right button.approve-btn');
            var $reject_btn = $('.asign-request .alt-header-right button.reject-btn');
            $approve_btn.prop('disabled', true);
            $reject_btn.prop('disabled', true);

            // Object Condition
            var objectMatchImageUpload = $('input[name="objectMatchImageUpload"]:checked').val();
            var object_match_imageupload_reason = $('.object_match_imageupload_reason').val();
            var objectMatchImageUploadReasonNotes = $('#objectMatchImageUploadReasonNotes').val();
            var object_condition = $('.object_condition').val();

            var noticeableDamages = $('input[name="noticeableDamages"]:checked').val();
            var object_noticeable_damage_reason = $('.object_noticeable_damage_reason').val();
            var objectNoticeableDamageReasonNotes = $('#objectNoticeableDamageReasonNotes').val();

            var asignProtectCondition = $('input[name="asignProtectCondition"]:checked').val();
            var object_asignprotect_condition_reason = $('.object_asignprotect_condition_reason').val();
            var asignProtectConditionReasonNotes = $('#asignProtectConditionReasonNotes').val();

            var surfaceSuitable = $('input[name="surfaceSuitable"]:checked').val();
            var surfaceLabelApplied = $('input[name="surfaceLabelApplied"]:checked').val();
            var material_frame = $('.material_frame').val();
            var materialFrameNotes = $('#materialFrameNotes').val();
            var material_stretcher = $('.material_stretcher').val();
            var materialStretcherNotes = $('#materialStretcherNotes').val();
            var material_stand = $('.material_stand').val();
            var materialStandNotes = $('#materialStandNotes').val();
            var objectAdditionalNotes = $('input[name="objectAdditionalNotes"]:checked').val();

            var object_surface_suitable_reason = $('.object_surface_suitable_reason').val();
            var objectSurfaceSuitableReasonNotes = $('#objectSurfaceSuitableReasonNotes').val();
            var objectAdditionalReasonNotes = $('#objectAdditionalReasonNotes').val();

            // Site Condition
            var adequatePhysicalSpace = $('input[name="adequatePhysicalSpace"]:checked').val();
            var site_adequatephysical_taskcomplete_reason = $('.site_adequatephysical_taskcomplete_reason').val();
            var adequatePhysicalSpaceReasonNotes = $('#adequatePhysicalSpaceReasonNotes').val();
            var adequatePhysicalAlternativeSpace = $('input[name="adequatePhysicalAlternativeSpace"]:checked').val();
            var adequatePhysicalAlternativespaceNotes = $('#adequatePhysicalAlternativespaceNotes').val();

            var smoothWorkflow = $('input[name="smoothWorkflow"]:checked').val();
            var site_smoothworkflow_reason = $('.site_smoothworkflow_reason').val();
            var smoothWorkflowReasonNotes = $('#smoothWorkflowReasonNotes').val();

            var entryPoints = $('#entryPoints').val();
            var exitPoints = $('#exitPoints').val();

            var lightingAdequate = $('input[name="lightingAdequate"]:checked').val();
            var site_lighting_adequate_reason = $('.site_lighting_adequate_reason').val();
            var lightingAdequateReasonNotes = $('#lightingAdequateReasonNotes').val();
            var lightingAdequateAlternativeSpace = $('input[name="lightingAdequateAlternativeSpace"]:checked').val();
            var lightingAdequateAlternativespaceNotes = $('#lightingAdequateAlternativespaceNotes').val();

            var surroundingWorkSpace = $('input[name="surroundingWorkSpace"]:checked').val();
            var site_surrounding_workspace_reason = $('.site_surrounding_workspace_reason').val();
            var surroundingWorkSpaceReasonNotes = $('#surroundingWorkSpaceReasonNotes').val();
            var surroundingWorkSpaceAlternativeSpace = $('input[name="surroundingWorkSpaceAlternativeSpace"]:checked').val();
            var surroundingWorkSpaceAlternativespaceNotes = $('#surroundingWorkSpaceAlternativespaceNotes').val();

            var safetyProtocols = $('input[name="safetyProtocols"]:checked').val();
            var emergencyExit = $('#emergencyExit').val();
            var securityRequirements = $('#securityRequirements').val();
            var observationSecurity = $('#observationSecurity').val();

            var washroomAvailable = $('input[name="washroomAvailable"]:checked').val();
            var locatedAndAccessed = $('#locatedAndAccessed').val();
            var nearestWashroom = $('#nearestWashroom').val();

            var networkCoverage = $('input[name="networkCoverage"]:checked').val();
            var alternateAvailableNetwork = $('#alternateAvailableNetwork').val();

            var siteAdditionalNotes = $('#siteAdditionalNotes').val();

            var applySiteCondition = $('input[name="applySiteCondition"]:checked').val();

            var checkConditionForApprove = (objectMatchImageUpload == 1
                && object_condition
                && (noticeableDamages == 0 || (noticeableDamages == 1 && object_noticeable_damage_reason && (object_noticeable_damage_reason != 22 || object_noticeable_damage_reason != 26 || object_noticeable_damage_reason != 29)) || (noticeableDamages == 1 && object_noticeable_damage_reason && (object_noticeable_damage_reason == 22 || object_noticeable_damage_reason == 26 || object_noticeable_damage_reason == 29) && objectNoticeableDamageReasonNotes != ''))
                && asignProtectCondition == 1
                && ((surfaceSuitable == 1
                    && surfaceLabelApplied != null
                    && (surfaceLabelApplied == 'Canvas' || ((material_frame && material_frame != 12) || (material_frame && material_frame == 12 && materialFrameNotes != '')
                        || (material_stretcher && material_stretcher != 14) || (material_stretcher && material_stretcher == 14 && materialStretcherNotes != '')
                        || (material_stand && material_stand != 25) || (material_stand && material_stand == 25 && materialStandNotes != '')
                    )) && objectAdditionalNotes != '')
                    || ((surfaceSuitable == 0 && object_surface_suitable_reason && object_surface_suitable_reason != '47')
                    || (surfaceSuitable == 0 && object_surface_suitable_reason && object_surface_suitable_reason == '47' && objectSurfaceSuitableReasonNotes != ''))
                    && objectAdditionalReasonNotes != '')
                && (adequatePhysicalSpace == 1
                    || ((adequatePhysicalSpace == 0 && site_adequatephysical_taskcomplete_reason && site_adequatephysical_taskcomplete_reason != 8 && adequatePhysicalAlternativeSpace == 1 && adequatePhysicalAlternativespaceNotes != '') || (adequatePhysicalAlternativeSpace == 0 && adequatePhysicalAlternativeSpace != ''))
                    || ((adequatePhysicalSpace == 0 && site_adequatephysical_taskcomplete_reason && site_adequatephysical_taskcomplete_reason == 8 && adequatePhysicalSpaceReasonNotes != '' && adequatePhysicalAlternativeSpace == 1 && adequatePhysicalAlternativespaceNotes != '') || (adequatePhysicalAlternativeSpace == 0 && adequatePhysicalAlternativeSpace != '')))
                && (smoothWorkflow == 1
                    || ((smoothWorkflow == 0 && site_smoothworkflow_reason && site_smoothworkflow_reason != 22)
                    || (smoothWorkflow == 0 && site_smoothworkflow_reason && site_smoothworkflow_reason == 22 && smoothWorkflowReasonNotes != '')))
                && entryPoints != ''
                && exitPoints != ''
                && (lightingAdequate == 1
                    || ((lightingAdequate == 0 && site_lighting_adequate_reason && site_lighting_adequate_reason != 13 && lightingAdequateAlternativeSpace == 1 && lightingAdequateAlternativespaceNotes != '') || (lightingAdequateAlternativeSpace == 0 && lightingAdequateAlternativeSpace != ''))
                    || ((lightingAdequate == 0 && site_lighting_adequate_reason && site_lighting_adequate_reason == 13 && lightingAdequateReasonNotes != '' && lightingAdequateAlternativeSpace == 1 && lightingAdequateAlternativespaceNotes != '') || (lightingAdequateAlternativeSpace == 0 && lightingAdequateAlternativeSpace != '')))
                && (surroundingWorkSpace == 1
                    || ((surroundingWorkSpace == 0 && site_surrounding_workspace_reason && site_surrounding_workspace_reason != 21 && surroundingWorkSpaceAlternativeSpace == 1 && surroundingWorkSpaceAlternativespaceNotes != '') || (surroundingWorkSpaceAlternativeSpace == 0 && surroundingWorkSpaceAlternativeSpace != ''))
                    || ((surroundingWorkSpace == 0 && site_surrounding_workspace_reason && site_surrounding_workspace_reason == 21 && surroundingWorkSpaceReasonNotes != '' && surroundingWorkSpaceAlternativeSpace == 1 && surroundingWorkSpaceAlternativespaceNotes != '') || (surroundingWorkSpaceAlternativeSpace == 0 && surroundingWorkSpaceAlternativeSpace != '')))
                && ((safetyProtocols == 1 && emergencyExit != '' && securityRequirements != '') || (safetyProtocols == 0 && safetyProtocols != '' && observationSecurity != ''))
                && ((washroomAvailable == 1 && locatedAndAccessed != '') || (washroomAvailable == 0 && washroomAvailable != '' && nearestWashroom != ''))
                && (networkCoverage == 1 || (networkCoverage == 0 && networkCoverage != '' && alternateAvailableNetwork != ''))
                && siteAdditionalNotes != ''
                && applySiteCondition == 1
            );

            if (checkConditionForApprove) {
                $approve_btn.prop('disabled', false);
            }

            var checkConditionForReject = ((objectMatchImageUpload != 1 && object_match_imageupload_reason && object_match_imageupload_reason != 15)
                || (objectMatchImageUpload != 1 && object_match_imageupload_reason == 15 && objectMatchImageUploadReasonNotes != '')
                || ((objectMatchImageUpload == 1 && object_asignprotect_condition_reason && object_asignprotect_condition_reason != 38) || (objectMatchImageUpload == 1 && object_asignprotect_condition_reason == 38 && asignProtectConditionReasonNotes != '') && (asignProtectCondition != 1 || asignProtectCondition == 'undefined'))
            );

            if (checkConditionForReject) {
                $reject_btn.prop('disabled', false);
            }
        } */

        // Provenance Tab - Authenticator Checklist
        $(".provenanceq_pov input[name$='provenanceObjectiveVerification']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".provenanceq_pov_divhide").show();
                $(".provenanceq_povr_divhide").hide();
                if ($(".provenanceq_paubou input[name$='provenanceArtUploadByOtherUser']").is(':checked') && $(".provenanceq_paubou input[name$='provenanceArtUploadByOtherUser']:checked").val() != 0) {
                    $(".provenanceq_paubou_divhide").show();
                } else {
                    $(".provenanceq_paubou_divhide").hide();
                }
            } else {
                $(".provenanceq_paubou input[name$='provenanceArtUploadByOtherUser']").prop('checked', false);
                $(".provenanceq_paubou_divhide input[name$='provenanceObjectNumberOfObject']").val('');
                $(".provenanceq_paubou_divhide input[name$='confirmIsObject']").removeAttr('checked');
                $(".provenanceq_povr_divhide select[name$='provenanceReason']").val(null).trigger("change");

                $(".provenanceq_pov_divhide").hide();
                $(".provenanceq_paubou_divhide").hide();
                $(".provenanceq_povr_divhide").show();
            }
        });

        $(".provenanceq_paubou input[name$='provenanceArtUploadByOtherUser']").on('click', function (e) {
            if ($(this).val() != 0) {
                $(".provenanceq_paubou_divhide").show();
            } else {
                $(".provenanceq_paubou_divhide").hide();
            }
        });

        $(document).ready(function () {
            var $form = $('#protect-request-inspection-form');

            $form.on('change keyup', function (e) {
                e.preventDefault();

                let data = $(this).serialize();
                $.ajax({
                    url: baseUrl + "/protect-request/{{ $data['id'] }}/inspection",
                    type: "POST",
                    data: data,
                    success: function (response) {
                        $('#view-header').html(response.data.header);
                    },
                    error: function (xhr) {
                        showErrorMessage(xhr);
                    },
                    complete: function (response) {
                    }
                });
            });
        });

        //Lightbox preview for inspection tab
        $('.inspection_view_box, .inspection_view_box_1, .inspection_view_box_2').viewbox({
            setTitle: false,
            margin: 40,
        });

        $(document).on('click', '.discard-btn', function (e) {
            $('.add-image-before').empty().html("<div class='drop-zone__prompt ff-medium'>Drop files to upload</div><div class='drop-zone__prompt-2 fs-14'>Or</div><div class='drop-zone__prompt-3 mt-3'><span> Select Files</span></div>");
            $('.drop-zone__thumb').remove();
        });

        /* $(document).on('click', '.discard-btn-alt', function (e) {
            $('.add-image-before-default').empty().html("<div class='drop-zone__prompt ff-medium'>Drop files to upload</div><div class='drop-zone__prompt-2 fs-14'>Or</div><div class='drop-zone__prompt-3 mt-3'><span> Select Files</span></div>");
            $('.drop-zone__thumb').remove();
        }); */

        $(document).on('click', '#imageObjectLabelModalToggle .btn-close', function (e) {
            $('#imageObjectLabelModalToggleLabel').empty().html("Add Image");
            $('#add-image-title').empty().html("Upload the photo and tag where the label needs to be place");
            $('.add-image-before').empty().html("<div class='drop-zone__prompt ff-medium'>Drop files to upload</div><div class='drop-zone__prompt-2 fs-14'>Or</div><div class='drop-zone__prompt-3 mt-3'><span> Select Files</span></div>");
            $('.drop-zone__thumb').remove();
            $('.discard-btn').hide();
            $('.change-image-btn').hide();
            $('#inspection-addimage-popup-2').hide();
            $('#inspection-addimage-popup-1').show();
            $('#context-menu ul li a').removeAttr('disabled');

            if (document.getElementById("upload_lable_file").files.length) {
                document.getElementById("upload_lable_file").value = '';
            }
        });

        $(document).on('click', '#addImageModal-2 .btn-close, #addImageModal-2 .cancel-btn', function (e) {
            $('#addImageModal-2 .add-image-before-default').empty().html("<div class='drop-zone__prompt ff-medium'>Drop files to upload</div><div class='drop-zone__prompt-2 fs-14'>Or</div><div class='drop-zone__prompt-3 mt-3'><span> Select Files</span></div>");
            $('#objectDamageImage .drop-zone__thumb').remove();
            $('.damage-change-image-btn-alt').hide();

            if (document.getElementById("objectDamageAdditionalImage").files.length) {
                document.getElementById("objectDamageAdditionalImage").value = '';
            }
        });

        $(document).on('click', '#addImageModal-3 .btn-close, #addImageModal-3 .cancel-btn', function (e) {
            $('#addImageModal-3 .add-image-before-default').empty().html("<div class='drop-zone__prompt ff-medium'>Drop files to upload</div><div class='drop-zone__prompt-2 fs-14'>Or</div><div class='drop-zone__prompt-3 mt-3'><span> Select Files</span></div>");
            $('#objectImage .drop-zone__thumb').remove();
            $('.object-change-image-btn-alt').hide();

            if (document.getElementById("objectAdditionalImage").files.length) {
                document.getElementById("objectAdditionalImage").value = '';
            }
        });

        $(document).on('click', '.change-image-btn', function (e) {
            $('#imageObjectLabelModalToggle .drop-zone .drop-zone__input').trigger('click');
        });

        $(document).on('click', '.damage-change-image-btn-alt', function (e) {
            $('#addImageModal-2 .drop-zone .drop-zone__input').trigger('click');
        });

        $(document).on('click', '.object-change-image-btn-alt', function (e) {
            $('#addImageModal-3 .drop-zone .drop-zone__input').trigger('click');
        });

        $(document).on('click', '.first-next-btn', function (e) {
            var $drop_zone_image = $('#inspection-addimage-popup-1 .drop-zone__thumb').clone();
            $('#inspection-addimage-popup-2 .popupimage-ctr .drop-zone.drop-zone1 .drop-zone__thumb').remove();
            $('#inspection-addimage-popup-2 .popupimage-ctr .drop-zone.drop-zone1 #container div').remove();
            $('#inspection-addimage-popup-2 .popupimage-ctr .drop-zone.drop-zone1').prepend($drop_zone_image);
        });

        $('#imageObjectLabelModalToggle').on('shown.bs.modal', function (e) {
            $('.discard-btn').show();
        });

        $(document).on('click', '.save-btn', function (e) {
            e.preventDefault();

            if (!$('.popupimage-ctr #container div').hasClass('authenticity_label') || !$('.popupimage-ctr #container div').hasClass('inventory_label')) {
                //$(this).attr('disabled', true);
                toastr.error('Add label types on the object');
                return false;
            }

            $('#inspection-addimage-popup-2 .drop-zone__thumb').css('border-radius', '0px');
            $('#inspection-addimage-popup-2 .delete-pop').hide('fast');
            $(this).attr('disabled', true);
            setTimeout(function () {
                html2canvas($("#inspection-addimage-popup-2 .drop-zone")[0]).then(function (canvas) {
                    var canvas_div = document.getElementById("inspection-addimage-popup-2").appendChild(canvas);
                    canvas_div.setAttribute("id", "canvas_gen");
                    canvas_div.style.display = 'none';
                    var canvas_gen = document.getElementById('canvas_gen');
                    var data_image = canvas_gen.toDataURL();
                    console.log(data_image);

                    $.ajax({
                        url: baseUrl + "/protect-request/{{ $data['id'] }}/inspection-file-upload",
                        type: "POST",
                        data: {'object_label_image': data_image},
                        beforeSend: function () {
                        },
                        success: function (response) {
                            if (response) {
                                var $image_preview = '<a href="' + response.data.full_url + '" class="inspection_view_box"><img src="' + response.data.full_url + '" alt="' + response.data.name + '" class="img-fluid"></a>';
                                $('.inspectionq_object_label .image-preview-list').removeClass('alt');
                                $('.inspectionq_object_label .image-preview-list').append($image_preview);
                                $('#inspection-addimage-popup-2 #canvas_gen').remove();

                                if (response.message) {
                                    toastr.success(response.message);
                                    $('#imageObjectLabelModalToggle .btn-close').trigger('click');
                                    $('.save-btn').attr('disabled', false);
                                    $(".inspection_view_box").unbind().removeData();
                                    $('.inspection_view_box').viewbox();
                                }
                            }
                        },
                        error: function (xhr) {
                            showErrorMessage(xhr);
                        }
                    });
                });
                if ($('.popupimage-ctr #container div').hasClass('authenticity_label') || $('.popupimage-ctr #container div').hasClass('inventory_label')) {
                    $('#inspection-addimage-popup-2 .delete-pop').show('fast');
                }
                $('#inspection-addimage-popup-2 .drop-zone__thumb').css('border-radius', '10px');
            }, 500);
        });

        $(document).on('submit', '#objectDamageImage', function (e) {
            e.preventDefault();

            $('.damage-apply-btn').attr('disabled', true);

            let data_image = new FormData(this);

            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/inspection-damage-file-upload",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: data_image,
                beforeSend: function () {
                },
                success: function (response) {
                    if (response) {
                        var $image_preview = '<a href="' + response.data.full_url + '" class="inspection_view_box_1"><img src="' + response.data.full_url + '" alt="' + response.data.name + '" class="img-fluid"></a>';
                        $('.inspectionq_ond_damage_images .damage-image-preview-list').removeClass('alt');
                        $('.inspectionq_ond_damage_images .damage-image-preview-list').append($image_preview);

                        if (response.message) {
                            toastr.success(response.message);
                            $('#addImageModal-2 .btn-close').trigger('click');
                            $('.damage-apply-btn').attr('disabled', false);
                            $(".inspection_view_box_1").unbind().removeData();
                            $('.inspection_view_box_1').viewbox();
                        }
                    }
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                }
            });
        });

        $(document).on('submit', '#objectImage', function (e) {
            e.preventDefault();

            $('.object-apply-btn').attr('disabled', true);

            let data_image = new FormData(this);

            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/inspection-object-file-upload",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: data_image,
                beforeSend: function () {
                },
                success: function (response) {
                    if (response) {
                        var $image_preview = '<a href="' + response.data.full_url + '" class="inspection_view_box_2"><img src="' + response.data.full_url + '" alt="' + response.data.name + '" class="img-fluid"></a>';
                        $('.inspectionq_ss_images .object-image-preview-list').removeClass('alt');
                        $('.inspectionq_ss_images .object-image-preview-list').append($image_preview);

                        if (response.message) {
                            toastr.success(response.message);
                            $('#addImageModal-3 .btn-close').trigger('click');
                            $('.object-apply-btn').attr('disabled', false);
                            $(".inspection_view_box_2").unbind().removeData();
                            $('.inspection_view_box_2').viewbox();
                        }
                    }
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                }
            });
        });

        $(document).on('click', ".inspectionq_ss input[name$='surfaceSuitable']", function (e) {
            if ($(this).val() == 0) {
                let data_image_type = 'object_label_images';

                $.ajax({
                    url: baseUrl + "/protect-request/{{ $data['id'] }}/inspection-remove-file-uploads",
                    type: "POST",
                    data: {data_image_type},
                    success: function (response) {
                        if (response.success) {
                            $('.inspectionq_object_label .image-preview-list').empty();
                            $('.inspectionq_object_label .image-preview-list').addClass('alt');
                        }
                    },
                    error: function (xhr) {
                        showErrorMessage(xhr);
                    }
                });
            }
        });

        $(document).on('click', ".inspectionq_ond input[name$='noticeableDamages']", function (e) {
            if ($(this).val() == 0) {
                let data_image_type = 'damage_images';

                $.ajax({
                    url: baseUrl + "/protect-request/{{ $data['id'] }}/inspection-remove-file-uploads",
                    type: "POST",
                    data: {data_image_type},
                    success: function (response) {
                        if (response.success) {
                            $('.inspectionq_ond_damage_images .damage-image-preview-list').empty();
                            $('.inspectionq_ond_damage_images .damage-image-preview-list').addClass('alt');
                        }
                    },
                    error: function (xhr) {
                        showErrorMessage(xhr);
                    }
                });
            }
        });

        $(document).on('click', ".inspectionq_ss input[name$='surfaceSuitable']", function (e) {
            if ($(this).val() == 1) {
                let data_image_type = 'object_images';

                $.ajax({
                    url: baseUrl + "/protect-request/{{ $data['id'] }}/inspection-remove-file-uploads",
                    type: "POST",
                    data: {data_image_type},
                    success: function (response) {
                        if (response.success) {
                            $('.inspectionq_ss_images .object-image-preview-list').empty();
                            $('.inspectionq_ss_images .object-image-preview-list').addClass('alt');
                        }
                    },
                    error: function (xhr) {
                        showErrorMessage(xhr);
                    }
                });
            }
        });

        $(document).ready(function () {
            $('#object_number').on('input', function (e) {

                if ($(this).val() != '') {
                    $('.object_number_check').show();
                } else {
                    $(".object_number_check input[name$='confirmIsObject']").removeAttr('checked');
                    $('.object_number_check').hide();
                }

                var asign_no = $('#object_number').val();

                $.ajax({
                    url: baseUrl + "/protect-request/{{ $data['id'] }}/provenance-objectnumber-check",
                    type: "POST",
                    data: {asign_no},
                    beforeSend: function () {
                        $(".object_number_check input[name$='confirmIsObject']").removeAttr('checked');
                        $('.object_number_check').hide();
                        $('.object_number_error').hide();
                    },
                    success: function (response) {
                        if (response) {
                            if (asign_no != response.data.asign_no) {
                                $('.object_number_error').show();
                                $('#object_number_hidden').val('');
                            } else {
                                $('.object_number_url').html(response.data.link);
                                $('.object_number_check').show();
                                $('.object_number_error').hide();
                                $('#object_number_hidden').val(asign_no);
                            }
                        }
                    },
                    error: function (xhr) {
                        showErrorMessage(xhr);
                    },
                    complete: function (response) {
                    }
                });
            });
        });

        $(document).ready(function () {
            var $form = $('#protect-request-provenance-form');

            $form.on('change keyup', function (e) {
                e.preventDefault();

                let data = $(this).serialize();
                $.ajax({
                    url: baseUrl + "/protect-request/{{ $data['id'] }}/provenance-authenticator",
                    type: "POST",
                    data: data,
                    success: function (response) {
                    },
                    error: function (xhr) {
                        showErrorMessage(xhr);
                    },
                    complete: function (response) {
                    }
                });
            });
        });

        $('#protect-request-inspection-form, #protect-request-provenance-form').on('keyup keypress', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        $('#applySiteCondition').on('click', function (e) {
            $(".site_condition_checklists input[name$='applySiteConditionForRequest']").prop('checked', false);
            if ($(this).is(':checked')) {
                /* if($(this).data('siteconditioncount') != 0) {
                    $("#siteConditionChecklist").modal('show');
                } */
                $("#siteConditionChecklist").modal('show');
            } else {
                $("#siteConditionChecklist").modal('hide');
            }
        });

        $(document).on('click', "#sc-apply-btn", function (e) {
            e.preventDefault();
            if ($(".site_condition_checklists input[name$='applySiteConditionForRequest']:checked")) {
                if ($(".site_condition_checklists input[name$='applySiteConditionForRequest']:checked").val() != 1) {
                    $('#siteConditionChecklist').modal('hide');
                }
            }
            let sitecondition_request_type = $(".site_condition_checklists input[name$='applySiteConditionForRequest']:checked").val();

            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/inspection-sitecondition-checklist-request-all",
                type: "POST",
                data: {sitecondition_request_type},
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.data);
                        $('#siteConditionChecklist').modal('hide');
                    }
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                }
            });
        });

        $(document).on('click', "#sccr-apply-btn, #sccrl-apply-btn", function (e) {
            e.preventDefault();
            var sitecondition_request_id = $(".site_condition_checklist_list input[name$='applySiteConditionForSingleRequest']:checked").val();
            var sitecondition_request_id_length = $(".site_condition_checklist_list input[name$='applySiteConditionForSingleRequest']:checked").length;
            
            $.ajax({
                url: baseUrl + "/protect-request/{{ $data['id'] }}/inspection-sitecondition-checklist-request",
                type: "POST",
                data: {sitecondition_request_id},
                beforeSend: function () {
                    $('#sccrl-apply-btn').prop('disabled', true);
                    if(sitecondition_request_id_length == '') {
                        toastr.error('Select anyone of the Site Condition Request.');
                        $('#sccrl-apply-btn').prop('disabled', false);
                        return false;
                    } else {
                        localStorage.tabactive1 = 1;
                    }
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#siteConditionChecklistRequest, #siteConditionChecklistRequestLists').modal('hide');
                        $('#sccrl-apply-btn').prop('disabled', false);
                        localStorage.tabactive2 = 1;
                        location.reload();
                    }
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                    $('#sccrl-apply-btn').prop('disabled', false);
                }
            });
        });

        $(document).ready(function () {
            $('#nav-inspection-tab[data-bs-target="nav-inspection"]').tab('show');
            /* $(".nav-tabs > .nav-link").on("shown.bs.tab", function(e) {
                var id = $(e.target).attr("aria-controls");
                window.location.hash = id;
            });

            var hash = window.location.hash;
            $('#nav-inspection-tab[data-bs-target="' + hash + '"]').tab('show'); */

            /* localStorage.tabactive1 = 0;
            $('button[data-bs-toggle="tab"]').on('click', function(e) {
                if(this.id == 'nav-inspection-tab') {
                    localStorage.tabactive1 = 1;
                }
            });console.log(localStorage.tabactive1);

            if(localStorage.tabactive1 == 1) {
                $('#nav-inspection-tab[data-bs-target="nav-inspection"]').tab('show');
            } */
        });
    </script>
@endpush
