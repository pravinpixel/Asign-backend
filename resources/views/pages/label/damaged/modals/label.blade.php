<form class="popup-form formFieldInput" id="popup_form">   
    <div class="mb-3">
        <label for="product_type" class="form-label">Product Type</label>
        <div class="w100Select">
            <select class="select2Box product_type" id="product_type" name="product_type" data-placeholder="Select Product">
                <option></option>
                @foreach($products as $pro => $product)
                    <option 
                        value="{{$product['id']}}" 
                        @if(in_array($product['id'], $added_products))
                            disabled
                        @endif
                    >
                        {{$product['name']}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="py-3 text-end">
        <button class="btn cancel-btn mx-2" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="product_submit_btn" class="btn apply-btn" disabled>Add</button>
    </div>
</form>
