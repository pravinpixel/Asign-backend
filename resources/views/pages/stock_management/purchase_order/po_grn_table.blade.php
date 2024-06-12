<table class="asign-table purchase-order-table" id="dynamic-po-pack-order">
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
        @forelse($poData['purchase_order_products'] as $index => $poProduct)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $poProduct['product_name'] }}</td>
                <td>{{ $poProduct['quantity'] }}</td>
                <td>{{ $poProduct['grn_quantity'] }}</td>
                @php
                        $segments = request()->segments();
                        $secondLastSegmentIndex = count($segments) - 2;
                        // second last segment
                        $grnId = $segments[$secondLastSegmentIndex] ?? null;
                       
                    @endphp
                <td>{{ $poProduct['grn_quantity_currently_scanned_products']  }}</td>
                <td>

                    @if ($poProduct['quantity'] === $poProduct['grn_quantity'] )
                        <a href="{{ route('grn.scan', [ 'product_id' => $poProduct['product_id'], 'order_product_id' => $poProduct['id'], 'grn_id' => $grnId, 'type' => 'po' ]) }}" class="btn apply-btn apply-btn-md" style="pointer-events: none; opacity: 0.5;cursor: not-allowed;">Scan</a>
                    @else
                        <a href="{{ route('grn.scan', [ 'product_id' => $poProduct['product_id'], 'order_product_id' => $poProduct['id'], 'grn_id' => $grnId, 'type' => 'po' ]) }}" class="btn apply-btn apply-btn-md">Scan</a>
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
