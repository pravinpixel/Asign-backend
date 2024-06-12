<div class="modal fade custom-modal location-modal" id="rejectProtectModal" tabindex="-1" aria-labelledby="bulkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="bulkModalLabel">Reject Asign Protect +</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="popup-form">
                    <div class="mb-3">
                        <label for="address" class="form-label">Please share the reason for Rejection</label>
                        <div class="w100Select">
                            <select id="address" class="form-select select2Box" placeholder="Select Reason">
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Add reason here ..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Select a Supervisor to Review the Rejection</label>
                        <div class="w100Select">
                            <select id="address1" class="form-select select2Box" placeholder="Select Supervisor">>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 text-end">
                        <button class="btn btn-outline-dark mx-2">Discard</button>
                        <button type="submit" class="btn btn-dark">Send for Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>