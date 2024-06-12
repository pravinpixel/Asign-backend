<div class="modal fade" id="delete_modal_confirm" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered confirm-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="delete_modal_confirm_label">Delete Label</h1>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                <section>
                    <p class="mb-3">Before deleting the label from the system, please remove the physical label from the object.</p>
                    <p class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="has_removed">
                        <label class="form-check-label" for="flexCheckDefault">
                            Physical label removed.
                        </label>
                    </div>
                    </p>
                    <p>Are you sure you want to delete?</p>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn apply-btn" disabled id="remove_btn">Delete</button>
            </div>
        </div>
    </div>
</div>