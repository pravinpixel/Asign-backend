@foreach ($purchase_order['label_order_details'] as $poProduct)

    {{-- @if (isset($poProduct) && count($poProduct['grn_relation']) > 0)
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
                @foreach ($purchase_order['purchase_order_products'] as $index => $product_detail)
                    <tr class="accordion_head un_focus">
                        @if (count($product_detail['grn_relation']) > 0)
                            <td width="8%">
                                <span class="arrow_indicator down">&nbsp;</span>
                            </td>
                        @else
                            <td width="8%"></td>
                        @endif
                        <td width="23%">{{ $index + 1 }}</td>
                        <td width="23%">{{ $product_detail->products->name }}</td>
                        <td width="23%">{{ $product_detail->quantity }}</td>
                        <td width="23%">{{ (count($product_detail['grn_relation']) > 0 ) ? $product_detail->grn_quantity : 0 }}</td>
                       
                    </tr>

                    @if (count($product_detail['grn_relation']) > 0)
                        <tr class="accordion_body">
                            <td colspan="7">
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

                                        @foreach ($product_detail['grn_relation'] as $grnProductData)
                                        @if($grnProductData['product_grn_quantity'] > 0)
                                            <tr>
                                                <td></td>
                                                <td>{{ \Carbon\Carbon::parse($grnProductData->created_at)->subDay()->format('d F Y') }}
                                                </td>
                                                <td>{{ $grnProductData['grn_no'] }}</td>
                                                <td></td>
                                                <td>{{ $grnProductData['product_grn_quantity'] }}</td>
                                              
                                            </tr>
                                        @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif
                    @endforeach

                </tbody>
            </table>
        @break;

    @else --}}

        <table class="asign-table purchase-order-table">
            <thead>
                <tr>
                    <th scope="col" width="8%"></th>
                    <th scope="col" width="23%">SR NO.</th>
                    <th scope="col" width="23%">PRODUCT NAME</th>
                    <th scope="col" width="23%">ORDER QUANTITY</th>
                    <th scope="col" width="23%">GRN QUANTITY</th>
                    <th scope="col" width="23%">STATUS</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($purchase_order['label_order_details'] as $index => $product_detail)
                    <tr class="">
                        {{-- @if (count($product_detail['grn_relation']) > 0)
                            <td width="8%">
                                <span class="arrow_indicator down">&nbsp;</span>
                            </td>
                        @else --}}
                            <td width="8%"></td>
                        {{-- @endif --}}
                        <td width="23%">{{ $index + 1 }}</td>
                        <td width="23%">{{ $product_detail->products->name }}</td>
                        <td width="23%">{{ $product_detail->qty }}</td>
                        {{-- <td width="23%">{{ (count($product_detail['grn_relation']) > 0 ) ? $product_detail->grn_quantity : 0 }}</td> --}}
                        <td>0</td>
                        {{-- @if (count($product_detail['grn_relation']) <= 0) --}}
                        <td >
                            @if ($product_detail->status == 'Open')
                                <span class="statusSkyblue statusCtr">{{ $product_detail->status ?? "" }}</span>
                            @elseif ($product_detail->status == 'Fulfilled')
                                <span class="statusYellow statusCtr">{{ $product_detail->status ?? "" }}</span>
                            @else
                                <span class="statusSkyblue statusCtr">{{ $product_detail->status ?? "Status not found" }}</span>     
                            @endif
                        </td>
                        {{-- @endif --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        @break;
    {{-- @endif --}}
@endforeach

