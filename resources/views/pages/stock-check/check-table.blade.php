@if(!isset($check->products) || count($check->products) == 0)
    <tr>
        <td colspan="5" class="txt-center empty-msg">Add Labels</td>
    </tr>
@else
    @foreach ($check->products as $k => $item)

        @php
            $color = ($item->on_hand < $item->qty) ? 'colorRed' : '';
            $scan_disabled = $disabled;
           if($check->status == 'enquiry-stop') {
               $scan_disabled = false;
           }

        @endphp

        <tr data-id="{{$item->product_id}}">
            <td>{{++$k}}</td>
            <td>
                {{$item->product?->name}}
            </td>
            <td>{{$item->qty}}</td>
            <td class="{{$color}}">{{$item->on_hand}}</td>
            <td>
                <a href="#" class="btn btn-outline-dark scan-product mx-2" {{$scan_disabled ? 'disabled' : ''}}>Scan</a>
                <a href="#" class="btn btn-outline-dark remove-product" {{$disabled ? 'disabled' : ''}}>Remove</a>
            </td>
        </tr>
    @endforeach

@endif
