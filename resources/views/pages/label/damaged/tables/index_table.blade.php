<table class="asign-table purchase-order-table">
    <thead>
    <tr>
        <th scope="col" width="20%" class="has_sort {{ $sortup["field"] == "reference" ? $sortup["value"] : "" }}" data-field="reference">REF NO.</th> 
        <th scope="col" width="20%" class="has_sort {{ $sortup["field"] == "date" ? $sortup["value"] : "" }}" data-field="date">DATE</th>
        <th scope="col" width="20%" class="has_sort {{ $sortup["field"] == "added_by" ? $sortup["value"] : "" }}" data-field="added_by">ADDED BY</th>
        <th scope="col" width="20%" class="has_sort {{ $sortup["field"] == "location" ? $sortup["value"] : "" }}" data-field="location">LOCATION</th>
        <th scope="col" width="20%">TOTAL DAMAGED LABELS</th>
    </tr>
    </thead>
    <tbody>
        @if (count($labels) > 0)
            @foreach($labels as $in => $lab)
                <tr>
                    <td>{{$lab["ref_no"]}}</td>
                    <td>{{$lab["date"]}}</td>
                    <td>{{$lab["added_by"]}}</td>
                    <td>{{$lab["location"]}}</td>
                    <td>{{$lab["total_damaged_label"]}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="txt-center empty-msg">Add Labels</td>
            </tr>
        @endif
    </tbody>
</table>