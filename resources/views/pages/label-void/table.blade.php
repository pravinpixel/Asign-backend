@foreach($data as $item)
<tr>
    <td>{{ $item->location->name ?? ''}}</td>
    <td>{{ $item->agent->name ?? ''}}</td>
    <td>{{ $item->envelope_code ?? ''}}</td>
    <td>{{ $item->label_code ?? ''}}</td>
    <td data-toggle="tooltip" data-placement="bottom" title="{{ $item->void_remarks}}">
        <p>
            {{ $item->void_remarks ?? '-'}}
        </p>
    </td>
</tr>
@endforeach