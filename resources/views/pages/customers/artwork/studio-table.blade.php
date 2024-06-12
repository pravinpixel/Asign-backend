@foreach ($data as $item)
    <tr class="row-class">
        <td>
        <div class="position-relative">
            <img src="{{config('app.image_url').$item->image}}" alt="" class="img-fluid table-row-image">
            @if($item->status =='approved')
                <img class="protect-table-inner" src="{{ asset('icons/protect.png') }}">
            @endif
        </div>    
        </td>
        <td>{{ $item->title  }}</td>
        <td>{{$item->asign_no}}</td>
        <td>{{$item->object_type ?? ''}}</td>
        <td> {{ \App\Helpers\UtilsHelper::convertDateTimeToDay($item->aging) }}</td>
        <td>{{$item->likes}}</td>
        <td>{{$item->views}}</td>
        <td>N/A</td>
    <tr>
@endforeach
