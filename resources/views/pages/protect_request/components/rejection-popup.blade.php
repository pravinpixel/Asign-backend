<div class="modal fade custom-modal location-modal" id="rejectProtectModal" tabindex="-1"
     aria-labelledby="bulkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="bulkModalLabel">Reject Asign Protect +</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form class="popup-form" id="rejection-form">

                    <div class="mb-3">
                        <label for="reject_reason_id" class="form-label">Please share the reason for Rejection</label>
                        <div class="w100Select">
                            <select id="reject_reason_id" name="reject_reason_id" data-placeholder="Select Reason"
                                    class="form-select select2Box" required>
                                <option value=""></option>
                                @foreach($master['reasons'] as $reason)
                                    <option
                                        value="{{$reason['id']}}">{{$reason['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea name="reject_reason_message" required class="form-control"
                                  id="exampleFormControlTextarea1"
                                  rows="3"
                                  placeholder="Add reason here ..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="supervisor_id" class="form-label">Select a Supervisor to Review the
                            Rejection</label>
                        <div class="w100Select">
                            <select id="supervisor_id" required name="reviewer_id" class="form-select select2Box"
                                    data-placeholder="Select Supervisor">
                                <option value=""></option>
                                @foreach($roles['supervisor'] as $role)
                                    <option value="{{$role['id']}}">{{$role['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-outline-dark mx-2">Discard</button>
                        <button type="submit" class="btn btn-dark">Send for Review</button>
                    </div>
                </form>


                <div class="mb-3" id="reviewer_name_div">

                </div>


            </div>
        </div>
    </div>
</div>

<div class="modal fade custom-modal location-modal" id="rejectApproveModal" tabindex="-1"
     aria-labelledby="bulkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="bulkModalLabel">Reject Asign Protect +</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="popup-form" id="reject-approve-form">
                    <div class="mb-5">
                        <textarea required name="reject_approve_message" class="form-control"
                                  id="exampleFormControlTextarea1" rows="7"
                                  placeholder="Add reason here ..."></textarea>
                    </div>
                    <div class="mb-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-outline-dark mx-2">Cancel</button>
                        <button type="submit" class="btn btn-dark">Send for Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="rejectOverrideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered confirmationPopup">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Override Rejection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="override-rejection-form">
                <div class="modal-body">
                    Overriding the Rejection will Approve this Request <br/> and move it to the next stage of the
                    process.
                    <br/>
                    <br/>
                    Are you sure you want to proceed by overriding?
                </div>
                <div class="modal-footer">
                    <div class="mb-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-outline-dark mx-2">Cancel</button>
                        <button type="submit" class="btn btn-dark">Override</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered confirmationPopup">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approve-form">
                <div class="modal-body">
                    Approving this Request will move it to the next stage of the process.
                    <br/>
                    <br/>
                    Are you sure you want to proceed by approving?
                </div>
                <div class="modal-footer">
                    <div class="mb-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-outline-dark mx-2">Cancel</button>
                        <button type="submit" class="btn btn-dark">Yes</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
