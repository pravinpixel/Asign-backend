@if ($orderStatus === "Packed")
    <table class="asign-table purchase-order-table" id="dynamic-sto-pack-order">
        <thead>
        <tr>
            <th scope="col" width="15%">SR. NO.</th>
            <th scope="col" width="30%">PRODUCT NAME</th>
            <th scope="col" width="30%">ORDER QUANTITY</th>
            <th scope="col" width="30%">ISSUE QUANTITY</th>
            {{-- <th scope="col" width="15%">ACTION</th> --}}
        </tr>
        </thead>
        <tbody>
            @forelse($stoData['stock_transfer_order_products'] as $index => $sto_product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sto_product['product']->name }}</td>
                    <td>{{ $sto_product['quantity'] }}</td>
                    <td>{{ $sto_product['scanned_req_quantity'] }}</td>
                    {{-- <td>
                        @php
                            $stoId = request()->segment(count(request()->segments()));
                        @endphp
                        @if ($sto_product['quantity'] === $sto_product['scanned_req_quantity'] )
                            <a href="{{ route('sto.pack.scan', [ 'sto_id' => $stoId, 'sto_product_id' => $sto_product['id'] ]) }}" class="btn apply-btn apply-btn-md" style="pointer-events: none; opacity: 0.5;cursor: not-allowed;">Scan</a>
                        @else
                            <a href="{{ route('sto.pack.scan', [ 'sto_id' => $stoId, 'sto_product_id' => $sto_product['id'] ]) }}" class="btn apply-btn apply-btn-md">Scan</a>
                        @endif
                    </td> --}}
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

@else

    <table class="asign-table purchase-order-table" id="dynamic-sto-pack-order">
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
                    <td>{{ $sto_product['quantity'] }}</td>
                    <td>{{ $sto_product['scanned_req_quantity'] }}</td>
                    <td>
                        @php
                            $stoId = request()->segment(count(request()->segments()));
                        @endphp
                        @if ($sto_product['quantity'] === $sto_product['scanned_req_quantity'] )
                            <a href="{{ route('sto.pack.scan', [ 'product_id' => $sto_product['product_id'], 'sto_id' => $stoId, 'sto_product_id' => $sto_product['id'] ]) }}" class="btn apply-btn apply-btn-md" style="pointer-events: none; opacity: 0.5;cursor: not-allowed;">Scan</a>
                        @else
                            <a href="{{ route('sto.pack.scan', [ 'product_id' => $sto_product['product_id'], 'sto_id' => $stoId, 'sto_product_id' => $sto_product['id'] ]) }}" class="btn apply-btn apply-btn-md">Scan</a>
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
@endif
