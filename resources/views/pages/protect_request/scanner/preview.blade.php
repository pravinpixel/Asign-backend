<div class="modal-content">
    <div class="modal-header">
        <h1 class="modal-title" id="popup_form_label">Asign Protect+ Labelling Process</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body labelling-process-modal-body">
        <section class="scanning-steps">
            <h4 class="scanning-header">There are 3 steps to labelling objects: </h4>
            <div class="step-card">
                <p class="step-card-title">Step 1: Object Image Match</p>
                <p class="step-card-content"><span><img src=" {{ asset('icons/camera.svg') }}" /></span> <span>Upload image</span></p>
            </div>
            <div class="step-card">
                <p class="step-card-title">Step 2: Apply Inventory Label</p>
                <p class="step-card-content"><span><img src="{{ asset('icons/qr.svg') }}" /></span> <span>Scan the codes, stick the label and upload an image</span></p>
            </div>
            <div class="step-card">
                <p class="step-card-title">Step 3: Apply Authenticity Label</p>
                <p class="step-card-content"><span><img src="{{ asset('icons/qr.svg') }}" /></span> <span>Scan the codes, stick the label and upload an image</span></p>
            </div>
            <h4 class="click-next">Click Next to start!</h4>
            <p class="icon-para"><span><img src="{{ asset('icons/warning-white.svg') }}" /></span> <span>All changes will be auto-saved</span></p>
        </section>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn apply-btn" id="next_step" data-next="object_match">Next</button>
    </div>
</div>