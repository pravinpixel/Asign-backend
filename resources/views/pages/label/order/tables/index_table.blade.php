@if ($total > 0)
    @foreach ($data as $purchase_order)
        <tr class="row-class" data-id="{{$purchase_order->id}}">
            <td>{{ $purchase_order->order_date }}</td>
            <td>{{ $purchase_order->order_no }}</td>
            <td>{{ $purchase_order->name }}</td>
            <td>{{ $purchase_order->location }}</td>
            <td>
                @if($purchase_order->status == 'Open')
                <span class="statusSkyblue statusCtr">{{ $purchase_order->status }}</span>
                @elseif ($purchase_order->status == 'Fulfilled')
                <span class="statusYellow statusCtr">{{ $purchase_order->status }}</span>
                @endif
            </td>
        </tr>
    @endforeach
@elseif($check_value_exists_table)
    <tr>
        <td colspan="5" class="txt-center empty-msg create-po">Create your first Purchase Order (PO)</td>
    </tr>
@else
    <tr>
        <td colspan="5" class="txt-center empty-msg">No Purchase Orders found!</td>
    </tr>
@endif
