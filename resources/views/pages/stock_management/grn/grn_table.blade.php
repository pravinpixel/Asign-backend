
        {{-- <tr>
            <td colspan="5" class="txt-center empty-msg">Create your first GRN (PO)</td>
        </tr> --}}
        @if ($total > 0)
            @foreach ( $data as $grn )
                <tr class="row-class" data-id="{{$grn->id}}">
                    <td>{{ $grn->grn_no }}</td>
                    <td>{{ $grn->order_no }}</td>
{{--                    <td>{{ ($grn->type == "po") ? $grn->purchase_order_no : $grn->sto_no }}</td>--}}
{{--                    <td>{{ ($grn->type == "po") ? $grn->manufacturers_name : $grn->sender_name }}</td>--}}
                    <td>{{ $grn->sender }}</td>
                    <td>{{ $grn->branch_name }}</td>
                    <td>{{ $grn->created_on }}</td>
                    {{-- <td>23 Dec, 2023</td> --}}
                </tr>
            @endforeach
        @elseif($check_value_exists_table)
            <tr>
                <td colspan="5" class="txt-center empty-msg create-po">Create your first Purchase Order (PO)</td>
            </tr>
        @else
            <tr>
                <td colspan="5" class="txt-center empty-msg">No Goods Received Notes found!</td>
            </tr>
        @endif

