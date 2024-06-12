<div class="modal fade custom-modal location-modal" id="product_sto_popup" tabindex="-1" aria-labelledby="product_popup_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="product_sto_popup_label">Add Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="popup-form formFieldInput" id="product_sto_modal" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label for="address" class="form-label">Product Name</label>
                        <div class="w100Select sto_popup">
                            <select id="select_sto_popup_product" class="select2Box popup-select-product" data-control="select2" data-placeholder="Select Product" name="product_name">
                                <option value=""></option>
                                @foreach ( $products as $product )
                                    <option value="{{ $product->name.",".$product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <span class="error" id="product_name_error"></span>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <label for="state" class="form-label">Quantity</label>
                        <input type="text" class="form-control" id="qty" placeholder="Enter Quantity" name="quantity">
                        <span class="error" id="quantity_name_error"></span>
                    </div>
                    <div class="mb-3">
                        <span class="availble_error" id="available_qty_error">

                        </span>
                    </div>
                   
                    <div class="py-3 text-end">
                        <button class="btn cancel-btn mx-2 sto-cancel-btn" type="reset">Cancel</button>
                        <button id="sto_available_btn" class="btn apply-btn product-sto-popup-available-btn">Check Availability</button>
                        <button id="sto_add_btn" class="btn apply-btn product-sto-popup-add-btn">Add</button>
                        <button id="sto_save_btn" class="btn apply-btn product-sto-popup-save-btn">save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/stock_management/sto.js') }}"></script>
<script type="text/javascript">
   var products = @json($products);
   var stoOrderedData = {};

</script>