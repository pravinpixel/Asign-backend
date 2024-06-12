<div class="modal fade custom-modal location-modal" id="product_popup" tabindex="-1" aria-labelledby="product_popup_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="product_popup_label">Add Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="popup-form formFieldInput" id="product_stock_form" autocomplete="off">
                    <div class="mb-3">
                        <input type="hidden" name="id">
                        <label for="product_id" class="form-label">Product Name</label>
                        <div class="w100Select">
                            <select id="product_id" class="select2Box popup-select-product" required data-control="select2" data-placeholder="Select Product" name="product_id">
                                <option value=""></option>
                            </select>
                            <span class="field-error" id="product_id_error"></span>
                        </div>
                    </div>
                    <div class="mb-3" id="qtyDiv">
                        <label for="qty" class="form-label">Quantity</label>
                        <input type="text" class="form-control" id="qty" onkeypress="return validateNumber(event)" placeholder="Enter Quantity" required name="qty">
                        <span class="field-error" id="qty_error"></span>
                    </div>
                    <div class="py-3 text-end">
                        <button type="button" class="btn cancel-btn mx-2"  data-bs-dismiss="modal" >Cancel</button>
                        <button type="button" class="btn apply-btn product-popup-check-btn ok-btn" id="checkAvailable" disabled>Check Availability</button>
                        <button type="submit" class="btn apply-btn product-popup-save-btn ok-btn" id="okAvailable" disabled>Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
