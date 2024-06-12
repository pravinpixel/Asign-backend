@if(!isset($label->products) || count($label->products) == 0)
    <tr>
        <td colspan="5" class="txt-center empty-msg">Select Requester Name or Request No.</td>
    </tr>
@else
    @foreach ($label->products as $k => $item)
        @php
            $balance_qty = $item->balance_qty + $item->returned_qty;
        @endphp

        <tr data-id="{{$item->product_id}}">
            <td>{{++$k}}</td>
            <td>
                {{$item->product?->name}}
            </td>
            <td>{{$balance_qty}}</td>
            <td>{{$item->returned_qty}}</td>
            <td>
                <button type="button" class="btn apply-btn scan-product" {{
                    $balance_qty == $item->returned_qty || $balance_qty == 0 ? 'disabled' : ''
                }}>Scan</button>
            </td>
        </tr>
    @endforeach

@endif
