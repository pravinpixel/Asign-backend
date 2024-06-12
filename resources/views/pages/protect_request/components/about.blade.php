@php
    $edit = isset($edit) ? $edit : false;
@endphp

<ul class="personal-info black-txt">
    <li>
        <span>Artist Name</span>
        <span>{{$data['artist']['display_name'] ?? $data['unknown_artist'] ?? ''}}</span>
    </li>
    <li>
        <span>Object Title</span>
        <span>{{$data['title']}}</span>
    </li>
    <li>
        <span>Object Type</span>
        <span>{{$data['type']['name'] ?? '' }}</span>
    </li>
    <li>
        <span>Creation Year</span>
        @if($edit)
            <span>
                <input  pattern="\d{4}" maxlength="4" type="text" name="creation_year_from"
                       value="{{$data['creation_year_from']}}"
                       class="form-control">
            </span>
        @else
            <span>{{$data['creation_year_from']}}</span>
        @endif
    </li>
    <li>
        <span>Completion Year</span>
        @if($edit)
            <span>
                <input pattern="\d{4}" maxlength="4" type="text" name="creation_year_to"
                       value="{{$data['creation_year_to']}}" class="form-control">
            </span>
        @else
            <span>{{$data['creation_year_to']}}</span>
        @endif
    </li>
    <li>
        <span>In Possession</span>
        <span>{{$data['is_your_possession'] ? 'Yes' : 'No'}}</span>
    </li>
    <li>
        <span>Description</span>
        <span>{{$data['description']}}</span>
    </li>

</ul>
