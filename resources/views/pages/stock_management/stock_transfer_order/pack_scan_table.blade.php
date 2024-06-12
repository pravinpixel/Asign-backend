<table class="asign-table purchase-order-table">
    <thead>
    <tr>
        <th scope="col" width="30%">PRODUCT ID</th>
        <th scope="col" width="30%">CATEGORY</th>
        <th scope="col" width="40%">LABEL QUANTITY</th>
    </tr>
    </thead>
    <tbody>
        {{-- <tr>
            <td colspan="4" class="txt-center empty-msg">Start Scanning Inventory QR Codes</td>
        </tr> --}}
        @forelse( $stoPackScanProducts as $stoPackScanProduct )
            <tr>
                <td>{{ $stoPackScanProduct['scanned_product_id'] ?? "-" }}</td>
                <td>Packet</td>
                <td>{{ $stoPackScanProduct['quantity'] ?? "-" }}</td>
            </tr>
        @empty
            <td colspan="4" class="txt-center empty-msg">Start Scanning</td>
        @endforelse
        {{-- <tr>
            <td>PIN364532</td>
            <td>Packet</td>
            <td>789</td>
        </tr><tr>
            <td>PIN364532</td>
            <td>Packet</td>
            <td>789</td>
        </tr><tr>
            <td>PIN364532</td>
            <td>Packet</td>
            <td>789</td>
        </tr> --}}
    </tbody>
</table>