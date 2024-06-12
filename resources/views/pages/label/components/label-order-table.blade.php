
@if(!isset($label->products) || count($label->products) == 0)
    <tr>
        <td colspan="4" class="txt-center empty-msg">Add Labels</td>
    </tr>
@else
    @foreach ($label->products as $k => $item)
        <tr data-id="{{$item->product_id}}">
            <td>{{++$k}}</td>
            <td>
              {{$item->product->name}}
            </td>
            <td>{{$item['qty']}}</td>
            <td>
                {{-- <a href="#" class="btn btn-outline-dark edit-product mx-2" {{$disabled ? 'disabled' : ''}}>Edit</a>

                <a href="#" class="btn btn-outline-dark remove-product" {{$disabled ? 'disabled' : ''}}>Remove</a> --}}
                <button type="button" class="btn cancel-btn edit-product-btn edit_modal_btn" data-bs-toggle="modal" data-bs-target="#product_popup">Edit</button>
            </td>
            <td>djskhfjdfjfjj</td>
        </tr>
    @endforeach

@endif
