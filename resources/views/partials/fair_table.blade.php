@if(count($data)>0)
    @foreach($data as $key=>$fair)
        <tr id="{{++$key}}">
            <td data-label="id">{{$key}}</td>
            <td><p>{{$fair->name}}</p></td>
            <td><button class="button-all align {{$fair->verify_status}}">{{$fair->status}}</button></td>
            <td class="edit_row"><a class="open_edit" data-id="{{$fair->id}}">Edit</a></td>
        </tr>
    @endforeach
@else
    <tr>
       <td colspan="4">No data</td>
    </tr>
@endif

<!-- echo "<h1>My Fuction<h1>"
$table.append('<tr  id=' + sr_no + '> 
	<td data-label="id">' + sr_no + '</td><td><p>' + row.name + '</p></td>'  + '
	<td><button class="button-all align ' + verify_status + '">' + status + '</button></td>
	<td class="edit_row"><a  onclick="get('+row.id+')"><img src="'+edit+'" width="18" /> Edit</a></td>
	</tr>');
?> -->