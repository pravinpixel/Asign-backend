<div class="modal fade custom-modal location-modal" id="product_popup" tabindex="-1" aria-labelledby="product_popup_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="product_popup_label">Add Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="popup-form formFieldInput" id="product_modal">
                    @csrf
                    <div class="mb-3">
                        <label for="address" class="form-label">Product Name</label>
                        <div class="w100Select">
                            <select id="select_popup_product" class="select2Box popup-select-product" data-control="select2" data-placeholder="Select Product" name="product_name">
                                <option value=""></option>
                                {{-- @foreach ( $products as $product )
                                    <option value="{{ $product->name.",".$product->id }}">{{ $product->name }}</option>
                                @endforeach --}}
                            </select>
                            <span class="error" id="product_name_error"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="state" class="form-label">Quantity</label>
                        <input type="text" class="form-control" id="qty" placeholder="Enter Quantity" name="quantity">
                        <span class="error" id="quantity_name_error"></span>
                    </div>
                    <div class="py-3 text-end">
                        <button class="btn cancel-btn mx-2" type="reset">Cancel</button>
                        <button id="submit_btn" class="btn apply-btn product-popup-save-btn" disabled>Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/stock_management/purchase_order.js') }}"></script>
<script type="text/javascript">
{{--   var products = @json($products);--}}
</script>
