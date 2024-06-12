


@foreach($data as $item)
<tr>
    <td>{{ $item->location->name ?? '' }}</td>
    <td>{{ $item->product->name }}</td>
    <td>{{ $item->scanned_product_id }}</td>
    <td>
        <span class="{{ $status[$item->status]['color'] ?? '' }} statusCtr"
              style="font-size: 13px">{{ $status[$item->status]['label'] ?? 'Pending' }}</span>
    </td>
</tr>
@endforeach