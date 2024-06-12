<div class="modal-content">
    <form id="scanner_form" class="scanner_form object_match_form" method="post" enctype="multipart/form-data">
        <input type="hidden" id="form_for" name="form_for" value="edit_uploaded_image_alt">
        <input type="hidden" id="this_image" name="this_image" value="{{$data['this_image']}}">
        <input type="hidden" id="from_step" name="from_step" value="{{$data['from_step']}}">
        <input type="hidden" id="img_type" name="img_type" value="{{$data['img_type']}}">
        <input type="hidden" id="temp_img_id" name="temp_img_id" value="{{$data['temp_img_id']}}">
        <input type="hidden" id="is_form_changed" name="is_form_changed" value="">
        <div class="modal-header">
            <h1 class="modal-title" id="popup_form_label">Step 1: Edit Uploaded Image</h1>
            <button id="close_scanner_modal" type="button" class="btn-close"></button>
        </div>
        <div class="modal-body" id="scanner_form_body">
            @include('pages.protect_request.scanner.forms.edit_form_alt')
        </div>
        <div class="modal-footer">
            <!-- Need to make dynamic below button-->
            <button type="button" class="btn cancel-btn"  id="close-image-cropper">Back</button>
            <button type="submit" class="btn apply-btn" id="submit_btn">Save</button>
        </div>
    </form>
</div>
