<table class="asign-table purchase-order-table" id="grn_create_table">
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
        @if (isset( $purchase_orders) )
            @foreach ( $purchase_orders->purchase_order_products as $index => $purchaseOrderProducts )
                <tr>
                    <td>{{ $index + 1 }}</td>
                    {{-- <td>{{ $purchaseOrderProducts->purchase_order_id }}</td>
                    <td>{{ $purchaseOrderProducts->product_id }}</td> --}}
                    <td>{{ $purchaseOrderProducts->products->name }}</td>
                    <td>{{ $purchaseOrderProducts->grn_quantity }}</td>
                    <td>
                        {{-- @php
                            $splitSegment = request()->segments();
                            $grnId = $splitSegment(count($splitSegment - 1));
                        @endphp --}}
                        @if ($purchaseOrderProducts->quantity === $purchaseOrderProducts->grn_quantity )
                            <a href="{{ route('grn.scan', ['order_product_id' => $purchaseOrderProducts->id, 'grn_id' => $grnId ]) }}" class="btn apply-btn apply-btn-md" style="pointer-events: none; opacity: 0.5;cursor: not-allowed;">Scan</a>
                        @else
                            <a href="{{ route('grn.scan', ['order_product_id' => $purchaseOrderProducts->id, 'grn_id' => $grnId ]) }}" class="btn apply-btn apply-btn-md">Scan</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="txt-center empty-msg">No Data found!</td>
            </tr> 
        @endif
        {{-- <tr>
            <td>2</td>
            <td>Authenticity Label</td>
            <td>0</td>
            <td>
                <a href="{{url('/goods-received-notes/scan/GRNO00001')}}" class="btn apply-btn apply-btn-md">Scan</a>
            </td>
        </tr> --}}
    </tbody>
</table>
