<div class="modal fade custom-modal imagematch-result-popup" id="image_match_modal_confirm" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered confirm-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="image_match_modal_confirm_label">Image Match Results</h1>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body py-0">
                <section>
                    <p>Your image match was less than 75%. Do you want to continue to labelling anyway?</p>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Back</button>
                <button type="button" class="btn apply-btn" id="next_step" data-next="inventory_label">Yes</button>
            </div>
        </div>
    </div>
</div>