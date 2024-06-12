@php
    $edit = isset($edit) ? $edit : false;
@endphp

<ul class="personal-info black-txt select-box">

    @if($edit)
        <li>
            <span>Signature</span>
            <span>
            <input type="text" name="signature" value="{{$data['signature']}}" class="form-control">
            </span>
        </li>
        <li>
            <span>Description</span>
            <span>
            <input type="text" name="description" value="{{$data['description']}}" class="form-control">
        </span>
        </li>
        <li>
            <span>Inscriptions</span>
            <span class="w100Select">
            <select name="is_inscription" data-placeholder="Select Inscriptions" class="form-control select2Box">
                <option value=""></option>
                <option value="1"  {{ $data['is_inscription'] == '1' ? 'selected' : ''  }} >Yes</option>
                <option value="0"  {{ $data['is_inscription'] == '0' ? 'selected' : ''  }}>No</option>
            </select>
        </span>
        </li>
        <li>
            <span>Verso</span>
            <span>
            <input type="text" name="verso_inscription" value="{{$data['verso_inscription']}}" class="form-control">
        </span>
        </li>
        <li>
            <span>Rector</span>
            <span>
            <input type="text" name="recto_inscription" value="{{$data['recto_inscription']}}" class="form-control">
        </span>
        </li>
        <li>
            <span>Base</span>
            <span>
            <input type="text" name="base_inscription" value="{{$data['base_inscription']}}" class="form-control">
        </span>
        </li>
    @else
        <li>
            <span>Signature</span>
            <span>{{$data['signature']}}</span>
        </li>
        <li>
            <span>Description</span>
            <span>{{$data['description']}}</span>
        </li>
        <li>
            <span>Inscriptions</span>
            <span>{{$data['is_inscription'] ? 'Yes' : 'No'}}</span>
        </li>
        <li>
            <span>Verso</span>
            <span>{{$data['verso_inscription']}}</span>
        </li>
        <li>
            <span>Rector</span>
            <span>{{$data['recto_inscription']}}</span>
        </li>
        <li>
            <span>Base</span>
            <span>{{$data['base_inscription']}}</span>
        </li>
    @endif

</ul>
