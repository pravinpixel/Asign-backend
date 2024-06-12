<table class="asign-table purchase-order-table">
    <thead>
    <tr>
        <th scope="col" width="10%">SR. NO.</th>
        <th scope="col">PRODUCT NAME</th>
        <th scope="col" width="40%">GRN QUANTITY</th>
    </tr>
    </thead>
    <tbody>
        {{-- <tr>
            <td colspan="4" class="txt-center empty-msg">Add Products</td>
        </tr> --}}
        @foreach ( $grnDetails->grn_relations as $index => $grnDetail )
            <tr>
                <td>{{ $index + 1 }}</td>
                @if ($type === "sto")
                    <td>{{ $grnDetail->sto_product_name }}</td>
                @else
                    <td>{{ $grnDetail->product_name }}</td>
                @endif   
                <td>{{ $grnDetail->product_grn_quantity }}</td>
            </tr>
            
        @endforeach
      
    </tbody>
</table>