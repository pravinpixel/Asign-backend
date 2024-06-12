<table class="asign-table purchase-order-table">
    <thead>
        <tr>
            <th scope="col" width="8%"></th>
            <th scope="col" width="23%">SR NO.</th>
            <th scope="col" width="23%">PRODUCT NAME</th>
            <th scope="col" width="23%">ORDER QUANTITY</th>
            <th scope="col" width="23%">GRN QUANTITY</th>
        </tr>
    </thead>
    <tbody>
</table>
<table class="asign-table purchase-order-table" id="accordion_tbl">
    <tbody>
        @php
            $index = 1;
        @endphp
        @foreach ($purchase_order_products as $product_detail)
            <tr class="accordion_head un_focus">
                <td width="8%">
                    <span class="arrow_indicator down">&nbsp;</span>
                </td>
                <td width="23%">{{ $index }}</td>
                <td width="23%">{{ $product_detail->products->name }}</td>
                <td width="23%">{{ $product_detail->quantity }}</td>
                <td width="23%">{{ $product_detail->grn_quantity }}</td>
            </tr>
            @if (isset($product_detail->grn))
                <tr class="accordion_body">
                    <td colspan="5">
                        <table class="asign-table-child purchase-order-table">
                            <thead>
                                <tr>
                                    <th width="8%"></th>
                                    <th width="23%">DATE</th>
                                    <th width="23%">GRN NO.</th>
                                    <th width="23%"></th>
                                    <th width="23%">GRN QUANTITY</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($product_detail->grn as $grn)
                                    <tr>
                                        <td></td>
                                        <td>{{ $grn->created_on }}</td>
                                        <td>{{ $grn->grn_no }}</td>
                                        <td></td>
                                        <td>{{ $grn->grn_quantity }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </td>
                </tr>
            @endif
                @php
                    $index++;
                @endphp
            @endforeach

    </tbody>
</table>
