@foreach ($data as $item)
    @php
        $invLabel = '';
        if (!is_null($item->inventory_label)) {
                    $inventory = json_decode($item->inventory_label, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE && isset($inventory['label'])) {
                        $invLabel = $inventory['label'];
                    }
                }
        $authLabel = '';
        if (!is_null($item->auth_label)) {
                    $auth = json_decode($item->auth_label, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE && isset($auth['label'])) {
                        $authLabel = $auth['label'];
                    }
                }
       $s = $status[$item->status];
   @endphp
   <tr class="row-class">
       <td>
            <div class="position-relative">
                <img src="{{config('app.image_url').$item->image}}" alt="" class="img-fluid table-row-image">
                @if($s['id']=='approved')
                <img class="protect-table-inner" src="{{ asset('icons/protect.png') }}">
                @endif
            </div>
       </td>

       <!-- <td>{{$item->image ?? ""}}</td> -->
       <td>{{$item->title}}</td>
       <td>{{$item->asign_no}}</td>
       <td>{{$item->name}}</td>
       <td>{{$item->city}}</td>
       <td>
           <span class="{{ $s['color'] }} statusCtr" style="font-size: 13px">{{ $s['label'] }}</span>
       </td>
       <td>{{$invLabel}}</td>
       <td>{{$authLabel}}</td>
   <tr>
@endforeach
