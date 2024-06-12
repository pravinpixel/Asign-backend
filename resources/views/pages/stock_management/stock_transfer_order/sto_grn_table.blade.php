<table class="asign-table purchase-order-table" id="dynamic-sto-pack-order">
    <thead>
    <tr>
        <th scope="col" width="15%">SR. NO.</th>
        <th scope="col" width="30%">PRODUCT NAME</th>
        <th scope="col" width="20%">ORDER QUANTITY</th>
        <th scope="col" width="20%">RECEIVED QUANTITY</th>
        <th scope="col" width="20%">GRN QUANTITY</th>
        <th scope="col" width="15%">ACTION</th>
    </tr>
    </thead>
    <tbody>
        @forelse($stoData['stock_transfer_order_products'] as $index => $sto_product)
            <tr>
                @php
                    $segments = request()->segments();
                    $secondLastSegmentIndex = count($segments) - 2;
                    // second last segment
                    $grnId = $segments[$secondLastSegmentIndex] ?? null;
                   
                @endphp
                <td>{{ $index + 1 }}</td>
                <td>{{ $sto_product['product']->name }}</td>
                <td>{{ $sto_product['quantity'] }}</td>
                <td>{{ $sto_product['grn_quantity'] }}</td>
                <td>{{ $sto_product['grn_quantity_currently_scanned_products'] }}</td>
                <td>
                   
                    @if ($sto_product['quantity'] === $sto_product['grn_quantity'] )
                        <a href="{{ route('grn.scan', [ 'product_id' => $sto_product['product_id'], 'order_product_id' => $sto_product['id'], 'grn_id' => $grnId, 'type' => 'sto' ]) }}" class="btn apply-btn apply-btn-md" style="pointer-events: none; opacity: 0.5;cursor: not-allowed;">Scan</a>
                    @else
                        <a href="{{ route('grn.scan', [ 'product_id' => $sto_product['product_id'], 'order_product_id' => $sto_product['id'], 'grn_id' => $grnId, 'type' => 'sto' ]) }}" class="btn apply-btn apply-btn-md">Scan</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="txt-center empty-msg">Add Products</td>
            </tr>
        @endforelse
        
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