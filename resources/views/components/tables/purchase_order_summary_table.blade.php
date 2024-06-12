<table class="asign-table purchase-order-table">
    <thead>
        <tr>
            {{-- <th scope="col" width="8%"></th> --}}
            <th scope="col" width="23%">SR NO.</th>
            <th scope="col" width="23%">PRODUCT NAME</th>
            <th scope="col" width="23%">ORDER QUANTITY</th>
            <th scope="col" width="23%">GRN QUANTITY</th>
        </tr>
    </thead>
    {{-- <tbody>
        <tr>
            <td colspan="5" class="txt-center empty-msg">Create your first Purchase Order (PO)</td>
        </tr>
    </tbody> --}}
</table>
<table class="asign-table purchase-order-table">
    <tbody>
        @foreach ( $createPoData['product_details'] as $index => $product_detail )
            <tr class="accordion_head un_focus">

                <td width="23%">{{ $index + 1 }}</td>
                <td width="23%">{{ $product_detail['product_name'] }}</td>
                <td width="23%">{{ $product_detail['quantity'] }}</td>
                <td width="23%">0</td>
            </tr>
        @endforeach

    </tbody>
</table>
