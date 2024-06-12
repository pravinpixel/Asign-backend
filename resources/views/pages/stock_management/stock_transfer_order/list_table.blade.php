@if ($total > 0)
    @foreach ($data as $transfer_order)
        <tr class="row-class" data-id="{{ $transfer_order->id }}">
            <td>{{ $transfer_order->sto_no }}</td>
            <td>{{ $transfer_order->created_date }}</td>
            <td>{{ $transfer_order->source_location }}</td>
            <td>{{ $transfer_order->to_location }}</td>
            <td>
                @if ($transfer_order->status == "Transit")
                    <span class="statusLavender statusCtr">{{ $transfer_order->status }}</span>
                @elseif ($transfer_order->status == "Fulfilled")
                    <span class="statusGreen statusCtr">{{ $transfer_order->status }}</span>
                @elseif ($transfer_order->status == "Packed")
                    <span class="statusOrange statusCtr">{{ $transfer_order->status }}</span>
                @else
                    <span class="statusYellow statusCtr">{{ $transfer_order->status }}</span>
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
