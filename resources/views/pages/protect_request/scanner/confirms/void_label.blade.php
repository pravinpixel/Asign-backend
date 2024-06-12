<div class="modal fade" id="void_modal_confirm" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered confirm-modal confirm-modal-void">
        <div class="modal-content">
            <form id="void_form" class="void_form" method="post">
                <input type="hidden" id="envelope_code" name="envelope_code">
                <input type="hidden" id="label_code" name="label_code">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="void_modal_confirm_label">Void Label</h1>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body" style="padding-top: 0px;">
                    <section>
                        <div class="container-fluid" style="padding: 0px;">
                            <p id="content"></p>
                            <p class="mb-2">Please share the reason for marking this label as void </p>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <select id="void_reason" name="void_reason_id" class="form-select" aria-label="Default select example">
                                        <option value="" selected disabled style="color: #363636; font-family: 'neue-montreal-medium!important'">Select a reason</option>
                                        @foreach ($reasons as $reason)
                                        <option value="{{ $reason->id }}">{{ $reason->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea id="void_remarks" name="void_remarks" class="form-control" rows="3" placeholder="Add reason here ..."></textarea>
                                </div>
                            </div>
                            <span id="error_message" style="color:red;"></span>
                        </div>
                    </section>
                </div>
                <div class="modal-footer">
                    <button type="button" id="c-btn" class="btn cancel-btn">Cancel</button>
                    <button type="submit" id="void_button" class="btn apply-btn" disabled>Mark as Void</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const voidReason = document.getElementById('void_reason');
        const voidRemarks = document.getElementById('void_remarks');
        const voidButton = document.getElementById('void_button');
        const cancelButton = document.getElementById('c-btn');

        function checkInputs() {
            if (voidReason.value && voidRemarks.value.trim() !== "") {
                voidButton.disabled = false;
                $('#content').addClass("mb-4");
                $('#content').text('Marking the label as Void means you will have to stick another label against this object. This action cannot be undone.');
            } else {
                voidButton.disabled = true;
                $('#content').removeclass("mb-4");
                $('#content').text('');
            }
        }

        cancelButton.addEventListener('click', function() {
            $('#void_form')[0].reset();
            voidButton.disabled = true;
            $('#error_message').text('');
            $('#content').text('');
            $('#void_modal_confirm').modal('hide');
        });

        voidReason.addEventListener('change', checkInputs);
        voidRemarks.addEventListener('input', checkInputs);
    });
</script>