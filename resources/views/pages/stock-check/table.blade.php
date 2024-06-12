@foreach ($data as $item)
    <tr class="row-class" data-id="{{ $item->id }}" data-status="{{$status[$item->status]['id']}}">
        <td>{{$item->request_id}}</td>
        <td>
            {{ \App\Helpers\UtilsHelper::displayDate($item->date, 'M, d, Y') }}
        </td>
        <td>{{ucfirst($item->type)}}</td>
        <td>{{$item->name}}</td>
        <td>
            <span class="{{$status[$item->status]['color']}} statusCtr">{{$status[$item->status]['label']}}</span>
        </td>
    </tr>
@endforeach
