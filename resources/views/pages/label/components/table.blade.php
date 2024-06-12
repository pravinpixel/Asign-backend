@if(count($data) == 0)
    <tr>
        <td colspan="4" class="txt-center empty-msg">Data not found</td>
    </tr>
@endif
@foreach ($data as $item)
    <tr class="row-class" data-id="{{ $item->id }}" data-status="{{$status[$item->status]['id']}}">
        <td>{{$item->request_id}}</td>
        <td>
            {{ \App\Helpers\UtilsHelper::displayDate($item->request_date, 'M, d, Y') }}
        </td>
        <td>{{$item->name}}</td>
        <td>
            <span class="{{$status[$item->status]['color']}} statusCtr">{{$status[$item->status]['label']}}</span>
        </td>
    </tr>
@endforeach
