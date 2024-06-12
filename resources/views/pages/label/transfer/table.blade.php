@foreach ($data as $item)
    <tr class="row-class" data-id="{{ $item->id }}" data-status="{{$status[$item->status]['id']}}">
        <td>{{$item->transfer_no}}</td>
        <td>
            {{ \App\Helpers\UtilsHelper::displayDate($item->date, 'M, d, Y') }}
        </td>
        <td>
            {{$item->source}}
        </td>
        <td>{{$item->destination}}</td>
        <td>
            <span class="{{$status[$item->status]['color']}} statusCtr">{{$status[$item->status]['label']}}</span>
        </td>
    </tr>
@endforeach
