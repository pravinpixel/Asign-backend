<div class="modal fade imagematch-result-popup" id="label_processing_modal_confirm" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered confirm-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="label_processing_modal_confirm_label">Labelling Process</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <section id="auth_inventory_content">
                    <p>You have successfully added the Inventory and Authenticity labels.</p>
                    <p class="mt-2">Do you want to Approve and complete the process or add child Inventory and Authenticity labels? After you Approve, no further changes can be made. </p>
                </section>
                <section id="auth_inventory_child_content">
                    <p>Labelling process complete, no further edits can be made.</p>
                    <p class="mt-2">Do you want to approve or add child labels?</p>
                </section>
            </div>
            <div class="modal-footer">
                <!-- id="next_step" data-next="inventory_label_child" -->
                <button type="button" class="btn cancel-btn" id="submit_and_add_child_label" data-status="false">Add Child Labels</button>
                <button type="button" class="btn apply-btn" id="approve_submit" data-status="true">Approve</button>
            </div>
        </div>
    </div>
</div>