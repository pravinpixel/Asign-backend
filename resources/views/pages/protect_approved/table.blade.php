@foreach ($data as $item)
    <tr class="row-class">
        <td>
            <div class="position-relative">
                <img src="{{config('app.image_url').$item->image}}" alt="" class="img-fluid table-row-image">
                <img class="protect-table-inner" src="{{ asset('icons/protect.png') }}">
            </div>
        </td>
        <td>{{ $item->artist_id ?  $item->full_name : $item->unknown_artist  }}</td>
        <td>{{$item->aa_no}}</td>
        <td>{{$item->asign_no}}</td>
        <td>{{ucfirst($item->account_type)}}</td>
        <td>N/A</td>
        <td>N/A</td>
        <td> {{ \App\Helpers\UtilsHelper::convertDateTimeToDaySeconds($item->aging) }}</td>
        <td>{{$item->likes}}</td>
        <td>{{$item->views}}</td>
        <td>N/A</td>
    <tr>
@endforeach
