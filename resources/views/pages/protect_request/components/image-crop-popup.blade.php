<div class="modal fade custom-modal image-modal" id="addImageModal-1" tabindex="-1" aria-labelledby="addImageModalLbl-1"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <form id="imageCropForm">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addImageModalLbl">Add Featured Image</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="cancel"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <img id="rc_img" name="rc_img" class="img-fluid" />
            </div>
            <div class="col-md-4">
              <input id="width" type="hidden">
              <input id="height" type="hidden">
              <input id="x" type="hidden">
              <input id="y" type="hidden">
              <img id="rc_preview" name="rc_preview" class="img-fluid" />
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button id="cancel" type="button" class="btn cancel-btn mx-2" data-bs-dismiss="modal">Close</button>
          <button id="update" type="submit" class="btn apply-btn">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade custom-modal image-modal" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLbl"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <form id="imageCropFormStep">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addImageModalLbl">Add Featured Image</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <div class="drop-zone">
              <div class="vstack justify-content-center gap-4 add-image-before">
                <div class="drop-zone__prompt ff-medium">
                  Drop files to upload
                </div>
                <div class="drop-zone__prompt-2 fs-14">
                  Or
                </div>
                <div class="drop-zone__prompt-3 mt-3">
                  <span> Select Files</span>
                </div>
              </div>
              <input type="file" name="image" class="image drop-zone__input object-image" id="additionalImage" accept="image/*" />
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
