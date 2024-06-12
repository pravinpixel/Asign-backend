<div class="modal fade" id="exist-model" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered confirmationPopup  modal-existing">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Existing Request</h5>
                <button type="button" class="btn-close" id="close-exist-model" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body-content">
                    <span id="agentName">Agent Name</span> currently has an active Request. To issue additional labels,
                    you can create
                    a new request, which will close the existing request and transfer any pending labels.
                    <br/>
                    <br/>
                    Do you want to create a new Request?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn apply-btn" data-bs-dismiss="modal" id="create-request">Create</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade artist-modal" id="confirm-modal" tabindex="-1" aria-labelledby="bulkModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md confirmationPopup ">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="bulkModalLabel">Alert</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-new">
                Are you sure you want to Reset the Labels?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn apply-btn" id="removeLabel">Yes</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="adjust-modal" tabindex="-1" aria-labelledby="bulkModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md confirmationPopup ">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 adjustHead" id="bulkModalLabel">Adjust Stock</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-new">
               Update Expected Stock quantity to reflect Actual Stock quantity?
            </div>
            <div class="modal-footer">
                <button type="button" data-value="override" class="btn apply-btn" id="overrideStock">Update</button>
            </div>
        </div>
    </div>
</div>

