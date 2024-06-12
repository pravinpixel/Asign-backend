@if(count($data) == 0)
    <tr>
        <td colspan="4" class="txt-center empty-msg">Data not found</td>
    </tr>
@endif
@foreach ($data as $item)
    <tr class="row-class" >
        <td>{{$item->request_id}}</td>
        <td>{{$item->artwork->title}}</td>
        <td>{{$item->customer->display_name}}</td>
        <td>{{$item->status}}</td> 
    </tr>
@endforeach