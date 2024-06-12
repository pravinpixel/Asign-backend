<div class="modal fade custom-modal image-modal" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLbl" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form> 
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addImageModalLbl">Add Featured Image</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button id="update" type="button" class="btn apply-btn">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
