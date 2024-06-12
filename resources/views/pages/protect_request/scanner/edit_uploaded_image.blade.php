<div class="modal-content">
    <form id="scanner_form" class="scanner_form object_match_form" method="post" enctype="multipart/form-data">
        <input type="hidden" id="form_for" name="form_for" value="edit_uploaded_image">
        <input type="hidden" id="is_form_changed" name="is_form_changed" value="">
        <div class="modal-header">
            <h1 class="modal-title" id="popup_form_label">Step 1: Edit Uploaded Image</h1>
            <button id="close_scanner_modal" type="button" class="btn-close"></button>
        </div>
        <div class="modal-body" id="scanner_form_body">
            @include('pages.protect_request.scanner.forms.edit_form')
        </div>
        <div class="modal-footer">
            <button type="button" class="btn cancel-btn" id="trigger_discard">Back</button>
            <button type="submit" class="btn apply-btn" id="submit_btn">Save</button>
        </div>
    </form>
</div>