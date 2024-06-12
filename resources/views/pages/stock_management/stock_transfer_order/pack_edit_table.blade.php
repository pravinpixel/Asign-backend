<table class="asign-table purchase-order-table" id="dynamic-sto-pack-ordered">
    <thead>
    <tr>
        <th scope="col" width="15%">SR. NO.</th>
        <th scope="col" width="30%">PRODUCT NAME</th>
        <th scope="col" width="30%">ORDER QUANTITY</th>
        <th scope="col" width="30%">ISSUE QUANTITY</th>
        <th scope="col" width="15%">ACTION</th>
    </tr>
    </thead>
    <tbody>
        @forelse($stoData['stock_transfer_order_products'] as $index => $sto_product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $sto_product['product']->name }}</td>
                <td style="display: none;">{{ $sto_product['product_id'] }}</td>
                <td>{{ $sto_product['quantity'] }}</td>
                <td>{{ $sto_product['grn_quantity'] }}</td>
                <td>
                  
                   <button type="button" class="btn edit-sto-pack-modal-btn" data-bs-toggle="modal" data-bs-target="#product_sto_popup">Edit</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="txt-center empty-msg">Add Products</td>
            </tr>
        @endforelse
        {{-- <tr>
            <td colspan="4" class="txt-center empty-msg">Add Products</td>
        </tr> --}}
        {{-- <tr>
            <td>1</td>
            <td>Inventory Label</td>
            <td>500</td>
            <td>
                <a href="" class="btn btn-outline-dark">Edit</a>
            </td>
        </tr> --}}
    </tbody>
</table>