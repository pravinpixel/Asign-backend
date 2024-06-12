@if(!isset($label->products) || count($label->products) == 0)
    <tr>
        <td colspan="5" class="txt-center empty-msg">Add Labels</td>
    </tr>
@else
    @foreach ($label->products as $k => $item)
        <tr data-id="{{$item->product_id}}">
            <td>{{++$k}}</td>
            <td>
                {{$item->product?->name}}
            </td>
            <td>{{$item->qty}}</td>
            <td>{{$item->issued_qty}}</td>
            <td>
                <button type="button" class="btn apply-btn scan-product"  {{$item->issued_qty >= $item->qty ? 'disabled' : ''}}
                    {{$disabled ? 'disabled' : ''}}>Scan</button>
{{--                <a href="#" class="btn btn-dark scan-product"--}}
{{--                    {{$item->issued_qty >= $item->qty ? 'disabled' : ''}}--}}
{{--                    {{$disabled ? 'disabled' : ''}}>Scan</a>--}}
            </td>
        </tr>
    @endforeach

@endif
