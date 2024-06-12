<div class="modal-content" id="void_label_confirm_modal">
    <div class="modal-header">
        <h5 class="modal-title">Void Label</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body content-new py-0">
        <form id="scanner_form" method="post">
            @csrf
            <p>Making the label as Void means you will have to stick another label against this object.This action cannot be undone.</p>
            <div class="mb-3">
                <label for="reject_reason_id" class="form-label">Please share the reason for making this label as void</label>
                <div class="w100Select reason-select">
                    <select id="reject_reason_id" name="reject_reason_id" data-placeholder="Select Reason" class="form-select select2Box" required>
                        <option value="">others</option>
                    </select>
                </div>
            </div>
            <div class="mb-2">
                <textarea required name="rejection_message" class="form-control" id="exampleFormControlTextarea1" rows="4" placeholder="Add reason here ..." style="resize: none;"></textarea>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="conform_exit">Cancel</button>
        <button type="button" class="btn apply-btn" data-bs-dismiss="modal">Mark as Void</button>
    </div>
</div>