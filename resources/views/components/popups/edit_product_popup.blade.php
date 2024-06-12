<div class="modal fade custom-modal location-modal product-edit-modal" id="product_edit_popup" tabindex="-1" aria-labelledby="product_edit_popup_label" aria-hidden="true">     
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="product_popup_label">Edit Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="popup-form formFieldInput" id="product_modal">
                    {{-- @csrf --}}
                    <div class="mb-3">
                        <label for="address" class="form-label">Product Name</label>
                        <div class="w100Select">
                            <select class="select2Box" data-placeholder="Select Product" name="product_name">
                                <option></option>
                                @foreach ( $products as $product )
                                    <option value="{{ $product->name.",".$product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">Quantity</label>
                        <input type="text" class="form-control" id="qty" placeholder="Enter Quantity" name="quantity">
                    </div>
                    <div class="py-3 text-end">
                        <button class="btn cancel-btn mx-2">Cancel</button>
                        <button id="submit_btn" class="btn apply-btn">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>