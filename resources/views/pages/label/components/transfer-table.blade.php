
@if(!isset($transfer->details) || count($transfer->details) == 0)
    <tr>
        <td colspan="4" class="txt-center empty-msg">Add Labels</td>
    </tr>
@else
    @foreach ($transfer->details as $k => $item)
        <tr data-id="{{$item->product_id}}">
            <td>{{++$k}}</td>
            <td>
              {{$item->product->name}}
            </td>
            <td>{{$item['qty']}}</td>
            <td>
                <a href="#" class="btn btn-outline-dark edit-product mx-2" {{$disabled ? 'disabled' : ''}}>Edit</a>

                <a href="#" class="btn btn-outline-dark remove-product" {{$disabled ? 'disabled' : ''}}>Remove</a>
            </td>
        </tr>
    @endforeach

@endif
