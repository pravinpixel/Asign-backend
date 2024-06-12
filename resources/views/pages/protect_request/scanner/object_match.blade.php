<div class="modal-content">
    <form id="scanner_form" class="scanner_form object_match_form" method="post" enctype="multipart/form-data">
        <input type="hidden" id="form_for" name="form_for" value="object_match">
        <div class="modal-header">
            <h1 class="modal-title" id="popup_form_label">Step 1: Upload Image to Match the Object</h1>
            <button id="close_scanner_modal" type="button" class="btn-close"></button>
        </div>
        <div class="modal-body" id="scanner_form_body">
            @include('pages.protect_request.scanner.forms.object_form')
        </div>
        <div class="modal-footer">
            <button type="button" class="btn cancel-btn" id="next_step" data-next="preview">Back</button>
            @if($data['percentage'] > 75 )
            <button type="button" class="btn apply-btn disabled-next" id="next_step" data-next="inventory_label">Next</button>
            @else
            <!-- <button type="button" class="btn apply-btn" disabled>Next</button> -->
            @if($data['object_img'])
            <button type="button" class="btn apply-btn" id="trigger_image_match">Next</button>
            @else
            <button type="button" class="btn apply-btn" disabled>Next</button>
            @endif
            @endif
        </div>
    </form>
</div>
